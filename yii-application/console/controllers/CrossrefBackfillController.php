<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Expression;

class CrossrefBackfillController extends Controller
{
    public int $limit = 200;   // 每次回填多少条
    public int $sleepMs = 200; // 请求间隔

    public function options($actionID)
    {
        return array_merge(parent::options($actionID), ['limit', 'sleepMs']);
    }

    public function actionRun(): int
    {
        $db = Yii::$app->db;

        // 挑“有 DOI 但 source_name/url/license/abstract 为空”的记录
        $rows = (new \yii\db\Query())
            ->from('works')
            ->select(['id', 'doi', 'source_name', 'url', 'license', 'abstract', 'crossref_id'])
            ->where(['not', ['doi' => null]])
            ->andWhere(['or',
                ['source_name' => null],
                ['url' => null],
                ['license' => null],
                ['abstract' => null],
                ['crossref_id' => null],
            ])
            ->limit($this->limit)
            ->all();

        if (!$rows) {
            $this->stdout("No rows to backfill.\n");
            return ExitCode::OK;
        }

        $done = 0;
        foreach ($rows as $r) {
            $id = (int)$r['id'];
            $doi = trim((string)$r['doi']);
            if ($doi === '') continue;

            try {
                $item = $this->fetchCrossrefByDoi($doi);

                // Crossref: container-title(期刊名)/publisher/URL/license/abstract(少量有)
                $source = $this->firstNonEmpty(
                    $this->firstArrayText($item['container-title'] ?? null),
                    $item['publisher'] ?? null
                );

                $url = $item['URL'] ?? null;

                $license = null;
                if (!empty($item['license']) && is_array($item['license'])) {
                    $license = $item['license'][0]['URL'] ?? ($item['license'][0]['content-version'] ?? null);
                }

                $abstract = $item['abstract'] ?? null;
                if (is_string($abstract)) {
                    // Crossref abstract 有时带 JATS 标签，简单去掉标签
                    $abstract = trim(strip_tags($abstract));
                    if ($abstract === '') $abstract = null;
                    if ($abstract !== null && mb_strlen($abstract, 'UTF-8') > 2000) {
                        $abstract = mb_substr($abstract, 0, 2000, 'UTF-8');
                    }
                } else {
                    $abstract = null;
                }

                $update = [];
                if ($r['source_name'] === null && $source) $update['source_name'] = $source;
                if ($r['url'] === null && $url) $update['url'] = $url;
                if ($r['license'] === null && $license) $update['license'] = $license;
                if ($r['abstract'] === null && $abstract) $update['abstract'] = $abstract;
                if ($r['crossref_id'] === null) $update['crossref_id'] = $doi; // 你表里 crossref_id 就先存 doi 也行

                if ($update) {
                    $update['updated_at'] = new Expression('CURRENT_TIMESTAMP');
                    $db->createCommand()->update('works', $update, ['id' => $id])->execute();
                    $done++;
                }

            } catch (\Throwable $e) {
                $this->stderr("Skip id={$id} doi={$doi}: {$e->getMessage()}\n");
            }

            usleep(max(0, $this->sleepMs) * 1000);
        }

        $this->stdout("DONE. updated={$done} / checked=" . count($rows) . "\n");
        return ExitCode::OK;
    }

    private function fetchCrossrefByDoi(string $doi): array
    {
        $url = 'https://api.crossref.org/works/' . rawurlencode($doi);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'User-Agent: Yii2-Crossref-Backfill/1.0',
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
        if (!is_array($json) || empty($json['message'])) {
            throw new \RuntimeException("Invalid JSON from Crossref");
        }
        return $json['message'];
    }

    private function firstArrayText($v): ?string
    {
        if (is_array($v) && !empty($v)) {
            $s = trim((string)$v[0]);
            return $s === '' ? null : $s;
        }
        return null;
    }

    private function firstNonEmpty(...$vals): ?string
    {
        foreach ($vals as $v) {
            if ($v === null) continue;
            $s = trim((string)$v);
            if ($s !== '') return $s;
        }
        return null;
    }
}
