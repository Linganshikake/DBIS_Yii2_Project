<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Expression;

/**
 * OpenAlex 批量导入：works + authors + keywords + 关联表
 *
 * 用法示例：
 *   php yii openalex-import/run "Second Sino-Japanese War" --max=500
 *   php yii openalex-import/run "War of Resistance against Japan" 1931 2025 --max=2000
 *   php yii openalex-import/batch --max=2000
 */
class OpenalexImportController extends Controller
{
    public string $mailto = '';      // 可留空；有邮箱更友好（可选）
    public int $perPage = 200;       // OpenAlex 单页最大 200
    public int $max = 1000;          // 每次任务最多导入多少条
    public int $sleepMs = 200;       // 每次请求间隔（毫秒）

    // 关键词阈值与数量（不要求高质量：阈值低一点、保留更多）
    public float $conceptMinScore = 0.30;
    public int $conceptMaxPerWork = 15;

    // 很轻的主题词过滤：命中任意一个即可（避免明显无关）
    public array $topicWords = [
        'war', 'japan', 'japanese', 'china', 'chinese', 'resistance',
        'nanjing', 'manchuria', 'occupation', 'massacre', 'military',
        '抗战', '抗日', '战争', '日本', '侵华', '南京', '屠杀',
    ];

    public function options($actionID)
    {
        return array_merge(parent::options($actionID), [
            'mailto', 'perPage', 'max', 'sleepMs',
            'conceptMinScore', 'conceptMaxPerWork',
        ]);
    }

    /**
     * 批量跑多个关键词（建议你直接用这个）
     */
    public function actionBatch(): int
    {
        $queries = [
            'Second Sino-Japanese War',
            'War of Resistance against Japan',
            'Sino-Japanese War',
            'Nanjing Massacre',
            'Eighth Route Army',
            'New Fourth Army',
            '抗日战争',
            '抗战',
            '南京大屠杀',
            '日军侵华',
        ];

        foreach ($queries as $q) {
            $this->stdout("\n=== Import query: {$q} ===\n");
            $this->actionRun($q, 1931, 2025);
        }

        return ExitCode::OK;
    }

    /**
     * 导入单个关键词
     *
     * @param string $query 关键词（位置参数，不是 --query）
     * @param int|null $fromYear 默认 1931
     * @param int|null $toYear   默认 2025
     */
    public function actionRun(string $query, ?int $fromYear = 1931, ?int $toYear = 2025): int
    {
        $db = Yii::$app->db;

        $cursor = '*';
        $imported = 0;
        $seen = 0;

        while ($imported < $this->max) {
            $url = $this->buildUrl($query, $cursor, $fromYear, $toYear);
            $data = $this->httpGetJson($url);

            if (!is_array($data) || empty($data['results'])) {
                $this->stdout("No results, stop.\n");
                break;
            }

            foreach ($data['results'] as $w) {
                if ($imported >= $this->max) break;
                $seen++;

                try {
                    $db->transaction(function () use ($w, &$imported) {
                        $workId = $this->upsertWork($w);
                        if ($workId <= 0) {
                            return; // 被轻过滤或缺 title
                        }

                        // authors
                        $authorships = $w['authorships'] ?? [];
                        $order = 1;
                        foreach ($authorships as $a) {
                            $name = trim((string)($a['author']['display_name'] ?? ''));
                            if ($name === '') continue;

                            $authorId = $this->getOrCreateAuthorId($name);

                            Yii::$app->db->createCommand()->upsert(
                                'work_authors',
                                [
                                    'work_id' => $workId,
                                    'author_id' => $authorId,
                                    'author_order' => $order,
                                ],
                                [
                                    'author_order' => $order,
                                ]
                            )->execute();

                            $order++;
                        }

                        // keywords：用 OpenAlex concepts（低门槛保留一定“意义”的关键词）
                        $concepts = $w['concepts'] ?? [];
                        $concepts = is_array($concepts) ? $concepts : [];

                        $concepts = array_filter($concepts, function ($c) {
                            $score = $c['score'] ?? 0;
                            return is_numeric($score) && (float)$score >= $this->conceptMinScore;
                        });

                        usort($concepts, function ($a, $b) {
                            return (float)($b['score'] ?? 0) <=> (float)($a['score'] ?? 0);
                        });

                        $kept = 0;
                        foreach ($concepts as $c) {
                            if ($kept >= $this->conceptMaxPerWork) break;

                            $kw = trim((string)($c['display_name'] ?? ''));
                            if ($kw === '') continue;

                            $keywordId = $this->getOrCreateKeywordId($kw);

                            Yii::$app->db->createCommand()->upsert(
                                'work_keywords',
                                [
                                    'work_id' => $workId,
                                    'keyword_id' => $keywordId,
                                ],
                                false
                            )->execute();

                            $kept++;
                        }

                        $imported++;
                    });

                } catch (\Throwable $e) {
                    // 某条失败不影响整体
                    $this->stderr("Skip one work due to error: {$e->getMessage()}\n");
                }
            }

            $cursor = $data['meta']['next_cursor'] ?? null;
            if (!$cursor) {
                $this->stdout("No next_cursor, stop.\n");
                break;
            }

            $this->stdout("Seen: {$seen}, Imported: {$imported}\n");
            usleep(max(0, $this->sleepMs) * 1000);
        }

        $this->stdout("DONE. Seen: {$seen}, Imported: {$imported}\n");
        return ExitCode::OK;
    }

    /**
     * 用 title_and_abstract.search 更聚焦
     */
    private function buildUrl(string $query, string $cursor, ?int $fromYear, ?int $toYear): string
    {
        $base = 'https://api.openalex.org/works';

        $params = [
            'per-page' => $this->perPage,
            'cursor' => $cursor,
        ];

        $filters = [];
        $filters[] = 'title_and_abstract.search:' . $query;

        if ($fromYear !== null) $filters[] = 'from_publication_date:' . $fromYear . '-01-01';
        if ($toYear !== null)   $filters[] = 'to_publication_date:' . $toYear . '-12-31';

        $params['filter'] = implode(',', $filters);

        if ($this->mailto !== '') {
            $params['mailto'] = $this->mailto;
        }

        return $base . '?' . http_build_query($params);
    }

    private function httpGetJson(string $url): array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'User-Agent: Yii2-OpenAlex-Importer/1.1',
            ],
        ]);
        $raw = curl_exec($ch);
        $err = curl_error($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($raw === false || $code >= 400) {
            throw new \RuntimeException("HTTP {$code}: {$err}");
        }

        $json = json_decode($raw, true);
        if (!is_array($json)) {
            throw new \RuntimeException("Invalid JSON response");
        }
        return $json;
    }

    /**
     * 写入 works：按 doi 或 openalex_id 去重更新
     * 返回 works.id；返回 0 表示跳过
     */
    private function upsertWork(array $w): int
    {
        $doi = $this->nullIfBlank($w['doi'] ?? null);
        $openalexId = $this->nullIfBlank($w['id'] ?? null);

        $title = trim((string)($w['title'] ?? ''));
        if ($title === '') return 0;

        // 发表年份/日期
        $year = $w['publication_year'] ?? null;
        $year = is_numeric($year) ? (int)$year : null;

        $pubDate = $this->nullIfBlank($w['publication_date'] ?? null);
        if ($pubDate !== null && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $pubDate)) {
            $pubDate = null;
        }

        // 文献类型 + 语言
        $type = $this->nullIfBlank($w['type'] ?? null) ?? 'other';
        $lang = $this->nullIfBlank($w['language'] ?? null);

        // 摘要：优先 abstract_inverted_index，没就留空
        $abstract = $this->extractAbstract($w);

        // 来源名：尽量取到“期刊/会议/出版社”
        $sourceName =
            $this->nullIfBlank($w['primary_location']['source']['display_name'] ?? null)
            ?? $this->nullIfBlank($w['host_venue']['display_name'] ?? null)
            ?? $this->nullIfBlank($w['primary_location']['source']['host_organization_name'] ?? null);

        // URL：优先落地页，其次 OA URL，再其次 DOI URL，再其次 OpenAlex 页面
        $landingPage =
            $this->nullIfBlank($w['primary_location']['landing_page_url'] ?? null)
            ?? $this->nullIfBlank($w['open_access']['oa_url'] ?? null)
            ?? $this->nullIfBlank($w['doi_url'] ?? null)
            ?? $openalexId;

        // 许可：OpenAlex open_access.license（没有就空）
        $license = $this->nullIfBlank($w['open_access']['license'] ?? null);

        // crossref_id：OpenAlex 有时提供“ids”，尽量取；没有就先空（后面用 Crossref 回填）
        $crossrefId =
            $this->nullIfBlank($w['ids']['pmid'] ?? null) // 如果你不想放 pmid，可删
            ?? null;

        $row = [
            'title' => $title,
            'title_alt' => null,
            'work_type' => $type,
            'publication_year' => $year,
            'publication_date' => $pubDate,
            'language' => $lang,
            'abstract' => $abstract,
            'source_name' => $sourceName,
            'doi' => $doi,                 // 空串已转 NULL
            'openalex_id' => $openalexId,
            'crossref_id' => $crossrefId,
            'url' => $landingPage,
            'license' => $license,
            'updated_at' => new \yii\db\Expression('CURRENT_TIMESTAMP'),
        ];


        $db = Yii::$app->db;

        // 查重：优先 doi，再 openalex_id
        $existing = null;
        if ($doi !== null) {
            $existing = (new \yii\db\Query())->from('works')->where(['doi' => $doi])->one();
        }
        if (!$existing && $openalexId !== null) {
            $existing = (new \yii\db\Query())->from('works')->where(['openalex_id' => $openalexId])->one();
        }

        if ($existing) {
            $db->createCommand()->update('works', $row, ['id' => $existing['id']])->execute();
            return (int)$existing['id'];
        }

        $db->createCommand()->insert('works', $row)->execute();
        return (int)$db->getLastInsertID();
    }

    private function matchAnyTopicWord(string $hayLower): bool
    {
        foreach ($this->topicWords as $tw) {
            $twLower = mb_strtolower((string)$tw, 'UTF-8');
            if ($twLower !== '' && mb_strpos($hayLower, $twLower, 0, 'UTF-8') !== false) {
                return true;
            }
        }
        return false;
    }

    private function getOrCreateAuthorId(string $name): int
    {
        $db = Yii::$app->db;
        $name = trim($name);

        $id = (new \yii\db\Query())->from('authors')->select('id')->where(['name' => $name])->scalar();
        if ($id) return (int)$id;

        $db->createCommand()->insert('authors', ['name' => $name])->execute();
        return (int)$db->getLastInsertID();
    }

    private function getOrCreateKeywordId(string $keyword): int
    {
        $db = Yii::$app->db;
        $keyword = trim($keyword);

        $id = (new \yii\db\Query())->from('keywords')->select('id')->where(['keyword' => $keyword])->scalar();
        if ($id) return (int)$id;

        $db->createCommand()->insert('keywords', ['keyword' => $keyword])->execute();
        return (int)$db->getLastInsertID();
    }

    private function extractAbstract(array $w): ?string
    {
        $inv = $w['abstract_inverted_index'] ?? null;
        if (!is_array($inv) || empty($inv)) return null;

        $posToWord = [];
        foreach ($inv as $word => $positions) {
            if (!is_array($positions)) continue;
            foreach ($positions as $p) {
                $posToWord[(int)$p] = $word;
            }
        }
        if (empty($posToWord)) return null;

        ksort($posToWord);
        $text = implode(' ', $posToWord);
        $text = trim(preg_replace('/\s+/', ' ', $text));

        if (mb_strlen($text, 'UTF-8') > 2000) {
            $text = mb_substr($text, 0, 2000, 'UTF-8');
        }
        return $text === '' ? null : $text;
    }

    private function nullIfBlank($v): ?string
    {
        if ($v === null) return null;
        $s = trim((string)$v);
        return $s === '' ? null : $s;
    }
}
