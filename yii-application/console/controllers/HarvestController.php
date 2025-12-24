<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Expression;
use common\models\Work;
use common\models\Author;
use common\models\WorkAuthor;
use common\models\Keyword;
use common\models\WorkKeyword;
use common\models\File;

class HarvestController extends Controller
{
    public int $pages = 3;
    public int $perPage = 200;
    public int $sleep = 1;
    public ?string $mailto = null;

    public function options($actionID): array
    {
        $opts = parent::options($actionID);
        if ($actionID === 'openalex') {
            $opts[] = 'pages';
            $opts[] = 'perPage';
            $opts[] = 'sleep';
            $opts[] = 'mailto';
        }
        return $opts;
    }

    public function optionAliases(): array
    {
        return [
            'p' => 'pages',
            'n' => 'perPage',
            's' => 'sleep',
            'm' => 'mailto',
        ];
    }

    /**
     * 导入 seed.csv
     * CSV 头建议：title,work_type,publication_year,publication_date,language,abstract,source_name,doi,url,license,authors,keywords
     * authors 用 ; 分隔，keywords 用 ; 分隔
     */
    public function actionSeed(string $csvPath): int
    {
        $real = $this->resolvePath($csvPath);
        if (!is_file($real)) {
            $this->stderr("CSV not found: {$real}\n");
            return ExitCode::DATAERR;
        }

        $fp = new \SplFileObject($real);
        $fp->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY);

        $header = null;
        $count = 0;
        foreach ($fp as $row) {
            if ($row === [null] || $row === false) continue;
            if ($header === null) {
                $header = array_map('trim', $row);
                continue;
            }
            $data = $this->rowToAssoc($header, $row);
            if (empty($data['title'])) continue;

            $this->upsertFromNormalized([
                'title' => $data['title'],
                'title_alt' => $data['title_alt'] ?? null,
                'work_type' => $data['work_type'] ?? 'journal-article',
                'publication_year' => $this->toIntOrNull($data['publication_year'] ?? null),
                'publication_date' => $data['publication_date'] ?? null,
                'language' => $data['language'] ?? null,
                'abstract' => $data['abstract'] ?? null,
                'source_name' => $data['source_name'] ?? null,
                'doi' => $this->normalizeDoi($data['doi'] ?? null),
                'openalex_id' => $data['openalex_id'] ?? null,
                'crossref_id' => $data['crossref_id'] ?? null,
                'url' => $data['url'] ?? null,
                'license' => $data['license'] ?? null,
                'authors' => $this->splitList($data['authors'] ?? ''),
                'keywords' => $this->splitList($data['keywords'] ?? ''),
                'files' => [],
            ]);
            $count++;
        }

        $this->stdout("Seed import done. Rows processed: {$count}\n");
        return ExitCode::OK;
    }

    /**
     * OpenAlex 批量采集入库（cursor 分页）
     * 用法：
     * php yii harvest/openalex "抗日战争" --pages=3 --perPage=200
     */
    public function actionOpenalex(string $query): int
    {
        $cursor = '*';
        $doneWorks = 0;

        for ($page = 1; $page <= $this->pages; $page++) {
            $url = $this->buildOpenAlexUrl($query, $cursor);
            $this->stdout("Fetching [{$page}/{$this->pages}] {$url}\n");

            $json = $this->httpGetJson($url);
            if ($json === null) {
                $this->stderr("Failed to fetch: {$url}\n");
                return ExitCode::UNAVAILABLE;
            }

            $results = $json['results'] ?? [];
            if (empty($results)) {
                $this->stdout("No more results.\n");
                break;
            }

            foreach ($results as $r) {
                $norm = $this->normalizeOpenAlexWork($r);
                if (empty($norm['title'])) continue;
                $this->upsertFromNormalized($norm);
                $doneWorks++;
            }

            $cursor = $json['meta']['next_cursor'] ?? null;
            if (!$cursor) break;

            if ($this->sleep > 0) sleep($this->sleep);
        }

        $this->stdout("OpenAlex harvest done. Works processed: {$doneWorks}\n");
        return ExitCode::OK;
    }

    // -------------------- 核心：统一入库逻辑 --------------------

    private function upsertFromNormalized(array $item): void
    {
        $tx = Yii::$app->db->beginTransaction();
        try {
            // 1) upsert works
            $work = $this->findWorkForUpsert($item);

            $work->title = $item['title'];
            $work->title_alt = $item['title_alt'] ?? null;
            $work->work_type = $item['work_type'] ?: 'journal-article';
            $work->publication_year = $item['publication_year'] ?? null;
            $work->publication_date = $item['publication_date'] ?? null;
            $work->language = $item['language'] ?? null;
            $work->abstract = $item['abstract'] ?? null;
            $work->source_name = $item['source_name'] ?? null;
            $work->doi = $item['doi'] ?? null;
            $work->openalex_id = $item['openalex_id'] ?? null;
            $work->crossref_id = $item['crossref_id'] ?? null;
            $work->url = $item['url'] ?? null;
            $work->license = $item['license'] ?? null;

            if (!$work->save()) {
                throw new \RuntimeException('Work save failed: ' . json_encode($work->errors, JSON_UNESCAPED_UNICODE));
            }

            // 2) authors link (幂等)
            if (!empty($item['authors'])) {
                // 清理旧的作者链接（可选）：为了保持顺序一致，这里简单重建
                WorkAuthor::deleteAll(['work_id' => $work->id]);

                $order = 1;
                foreach ($item['authors'] as $a) {
                    $author = $this->upsertAuthor($a);
                    $wa = new WorkAuthor([
                        'work_id' => $work->id,
                        'author_id' => $author->id,
                        'author_order' => $order++,
                        'role' => 'author',
                    ]);
                    if (!$wa->save()) {
                        throw new \RuntimeException('WorkAuthor save failed: ' . json_encode($wa->errors, JSON_UNESCAPED_UNICODE));
                    }
                }
            }

            // 3) keywords link (幂等)
            if (!empty($item['keywords'])) {
                WorkKeyword::deleteAll(['work_id' => $work->id]);

                foreach ($item['keywords'] as $kw) {
                    $keyword = $this->upsertKeyword($kw);
                    $wk = new WorkKeyword([
                        'work_id' => $work->id,
                        'keyword_id' => $keyword->id,
                    ]);
                    if (!$wk->save()) {
                        throw new \RuntimeException('WorkKeyword save failed: ' . json_encode($wk->errors, JSON_UNESCAPED_UNICODE));
                    }
                }
            }

            // 4) files（这里做“增量幂等”：同 work_id + url 不重复）
            if (!empty($item['files'])) {
                foreach ($item['files'] as $f) {
                    $exists = File::find()
                        ->where(['work_id' => $work->id])
                        ->andWhere(['url' => $f['url']])
                        ->exists();
                    if ($exists) continue;

                    $file = new File([
                        'work_id' => $work->id,
                        'file_type' => $f['file_type'],
                        'label' => $f['label'] ?? null,
                        'url' => $f['url'],
                        'license' => $f['license'] ?? null,
                    ]);
                    if (!$file->save()) {
                        throw new \RuntimeException('File save failed: ' . json_encode($file->errors, JSON_UNESCAPED_UNICODE));
                    }
                }
            }

            $tx->commit();
        } catch (\Throwable $e) {
            $tx->rollBack();
            throw $e;
        }
    }

    private function findWorkForUpsert(array $item): Work
    {
        $doi = $item['doi'] ?? null;
        if ($doi) {
            $existing = Work::findOne(['doi' => $doi]);
            if ($existing) return $existing;
        }

        // 无 DOI：用 title + year + type 做软去重
        $title = $item['title'] ?? '';
        $year = $item['publication_year'] ?? null;
        $type = $item['work_type'] ?? null;

        $q = Work::find()->where(['title' => $title]);
        if ($year !== null) $q->andWhere(['publication_year' => $year]);
        if ($type) $q->andWhere(['work_type' => $type]);

        $existing = $q->one();
        return $existing ?: new Work();
    }

    private function upsertAuthor(array $a): Author
    {
        $name = trim((string)($a['name'] ?? ''));
        if ($name === '') {
            throw new \InvalidArgumentException('Author name empty');
        }

        $openalexId = $a['openalex_id'] ?? null;
        if ($openalexId) {
            $found = Author::findOne(['openalex_id' => $openalexId]);
            if ($found) {
                if ($found->name !== $name) $found->name = $name;
                $found->save(false);
                return $found;
            }
        }

        $found = Author::find()->where(['name' => $name])->one();
        if ($found) return $found;

        $author = new Author([
            'name' => $name,
            'openalex_id' => $openalexId,
        ]);
        if (!$author->save()) {
            throw new \RuntimeException('Author save failed: ' . json_encode($author->errors, JSON_UNESCAPED_UNICODE));
        }
        return $author;
    }

    private function upsertKeyword(string $kw): Keyword
    {
        $kw = trim($kw);
        if ($kw === '') {
            throw new \InvalidArgumentException('Keyword empty');
        }

        $found = Keyword::findOne(['keyword' => $kw]);
        if ($found) return $found;

        $k = new Keyword(['keyword' => $kw]);
        if (!$k->save()) {
            throw new \RuntimeException('Keyword save failed: ' . json_encode($k->errors, JSON_UNESCAPED_UNICODE));
        }
        return $k;
    }

    // -------------------- OpenAlex 解析与网络 --------------------

    private function buildOpenAlexUrl(string $query, string $cursor): string
    {
        $q = rawurlencode($query);
        $c = rawurlencode($cursor);
        $base = "https://api.openalex.org/works?search={$q}&per-page={$this->perPage}&cursor={$c}";
        if ($this->mailto) {
            $base .= "&mailto=" . rawurlencode($this->mailto);
        }
        return $base;
    }

    private function httpGetJson(string $url): ?array
    {
        $ctx = stream_context_create([
            'http' => [
                'timeout' => 20,
                'header' => "User-Agent: Yii2-Console-Harvester\r\n",
            ],
        ]);
        $raw = @file_get_contents($url, false, $ctx);
        if ($raw === false) return null;
        $json = json_decode($raw, true);
        return is_array($json) ? $json : null;
    }

    private function normalizeOpenAlexWork(array $r): array
    {
        $title = $r['title'] ?? '';
        $type = $r['type'] ?? 'journal-article';
        $year = $r['publication_year'] ?? null;
        $lang = $r['language'] ?? null;

        $abstract = null;
        if (!empty($r['abstract_inverted_index']) && is_array($r['abstract_inverted_index'])) {
            $abstract = $this->rebuildAbstractFromInvertedIndex($r['abstract_inverted_index']);
        }

        $doi = null;
        $ids = $r['ids'] ?? [];
        if (!empty($ids['doi'])) {
            $doi = $this->normalizeDoi($ids['doi']);
        }

        $openalexId = $r['id'] ?? null;

        $sourceName = null;
        $url = null;
        if (!empty($r['primary_location'])) {
            $url = $r['primary_location']['landing_page_url'] ?? null;
            $sourceName = $r['primary_location']['source']['display_name'] ?? null;
        }

        $authors = [];
        $authorships = $r['authorships'] ?? [];
        foreach ($authorships as $a) {
            $aname = $a['author']['display_name'] ?? null;
            if (!$aname) continue;
            $authors[] = [
                'name' => $aname,
                'openalex_id' => $a['author']['id'] ?? null,
            ];
        }

        $keywords = [];
        $concepts = $r['concepts'] ?? [];
        foreach ($concepts as $c) {
            $kw = $c['display_name'] ?? null;
            if ($kw) $keywords[] = $kw;
            if (count($keywords) >= 10) break;
        }

        $files = [];
        $oa = $r['open_access'] ?? [];
        if (!empty($oa['oa_url'])) {
            $files[] = [
                'file_type' => 'external_link',
                'label' => 'Open access',
                'url' => $oa['oa_url'],
                'license' => $oa['license'] ?? null,
            ];
        }

        return [
            'title' => $title,
            'title_alt' => null,
            'work_type' => $type,
            'publication_year' => $year,
            'publication_date' => $r['publication_date'] ?? null,
            'language' => $lang,
            'abstract' => $abstract,
            'source_name' => $sourceName,
            'doi' => $doi,
            'openalex_id' => $openalexId,
            'crossref_id' => null,
            'url' => $url,
            'license' => $oa['license'] ?? null,
            'authors' => $authors,
            'keywords' => $keywords,
            'files' => $files,
        ];
    }

    private function rebuildAbstractFromInvertedIndex(array $inv): string
    {
        // inv: word => [pos1, pos2...]
        $posToWord = [];
        foreach ($inv as $word => $positions) {
            foreach ($positions as $p) {
                $posToWord[(int)$p] = $word;
            }
        }
        ksort($posToWord);
        return implode(' ', $posToWord);
    }

    // -------------------- 工具函数 --------------------

    private function resolvePath(string $p): string
    {
        // 允许 @app 别名
        if (str_starts_with($p, '@')) {
            return Yii::getAlias($p);
        }
        return $p;
    }

    private function rowToAssoc(array $header, array $row): array
    {
        $out = [];
        foreach ($header as $i => $key) {
            if ($key === '') continue;
            $out[$key] = isset($row[$i]) ? trim((string)$row[$i]) : '';
        }
        return $out;
    }

    private function splitList(string $s): array
    {
        $s = trim($s);
        if ($s === '') return [];
        $parts = preg_split('/[;；]\s*/u', $s);
        $parts = array_map('trim', $parts);
        $parts = array_values(array_filter($parts, fn($x) => $x !== ''));
        return $parts;
    }

    private function toIntOrNull($v): ?int
    {
        if ($v === null) return null;
        $v = trim((string)$v);
        if ($v === '') return null;
        return (int)$v;
    }

    private function normalizeDoi(?string $doi): ?string
    {
        if ($doi === null) return null;
        $doi = trim($doi);
        if ($doi === '') return null;

        // 可能是 https://doi.org/xxx
        $doi = preg_replace('#^https?://(dx\.)?doi\.org/#i', '', $doi);
        return $doi;
    }
}
