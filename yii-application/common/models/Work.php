<?php

namespace common\models;

use yii\db\ActiveRecord;

class Work extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'works';
    }

    public function rules(): array
    {
        return [
            [['title', 'work_type'], 'required'],
            [['abstract', 'url'], 'string'],
            [['publication_year'], 'integer'],
            [['publication_date'], 'safe'],
            [['title', 'title_alt'], 'string', 'max' => 512],
            [['work_type'], 'string', 'max' => 32],
            [['language'], 'string', 'max' => 16],
            [['source_name'], 'string', 'max' => 256],
            [['doi', 'openalex_id', 'crossref_id', 'license'], 'string', 'max' => 128],
            [['doi'], 'unique'],
        ];
    }

    public function getWorkAuthors()
    {
        return $this->hasMany(WorkAuthor::class, ['work_id' => 'id'])
            ->orderBy(['author_order' => SORT_ASC]);
    }

    public function getAuthors()
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->via('workAuthors');
    }

    public function getKeywords()
    {
        return $this->hasMany(Keyword::class, ['id' => 'keyword_id'])
            ->viaTable('work_keywords', ['work_id' => 'id']);
    }

    public function getFiles()
    {
        return $this->hasMany(File::class, ['work_id' => 'id']);
    }

    /**
     * 清洗空字符串 -> NULL，避免唯一索引/日期字段出问题
     * 同时（可选）做“标题/摘要翻译为中文”的自动填充（不依赖外部服务也不会报错）
     */
    public function beforeSave($insert)
    {
        foreach (['doi', 'openalex_id', 'crossref_id'] as $f) {
            if (isset($this->$f) && trim((string)$this->$f) === '') {
                $this->$f = null;
            }
        }

        if (isset($this->publication_date) && trim((string)$this->publication_date) === '') {
            $this->publication_date = null;
        }

        // ===== 可选：自动翻译（安全降级：没有翻译组件也不报错）=====
        // 说明：
        // 1) 如果你给 works 表加了 title_zh / abstract_zh 字段，这里会自动写入。
        // 2) 如果你没加字段，这段会自动跳过，不影响保存。
        // 3) 翻译需要你自己配置 Yii::$app->translator（可以先不做）。
        $this->tryAutoTranslateToZh();

        return parent::beforeSave($insert);
    }

    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'title' => '标题',
            'title_alt' => '外文标题/别名',
            'work_type' => '文献类型',
            'publication_year' => '发表年份',
            'publication_date' => '发表日期',
            'language' => '语种',
            'abstract' => '摘要',
            'source_name' => '来源/刊物',
            'doi' => 'DOI',
            'openalex_id' => 'OpenAlex ID',
            'crossref_id' => 'Crossref ID',
            'url' => '原文链接',
            'license' => '许可协议',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    // ====== 让“值”也中文化（展示用） ======

    public static function workTypeMap(): array
    {
        return [
            'journal-article'   => '期刊论文',
            'article'           => '文章',
            'book'              => '图书',
            'book-chapter'      => '图书章节',
            'thesis'            => '学位论文',
            'report'            => '报告',
            'newspaper-article' => '报纸文章',
            'archive'           => '档案',
            'dataset'           => '数据集',
            'other'             => '其他',
        ];
    }

    public function getWorkTypeText(): string
    {
        $map = self::workTypeMap();
        $v = (string)$this->work_type;
        return $map[$v] ?? ($v === '' ? '未填写' : $v);
    }

    public static function languageMap(): array
    {
        return [
            'zh' => '中文',
            'en' => '英文',
            'ja' => '日文',
            'fr' => '法文',
            'de' => '德文',
            'ru' => '俄文',
            ''   => '未填写',
            null => '未填写',
        ];
    }

    public function getLanguageText(): string
    {
        $map = self::languageMap();
        $v = $this->language;
        return $map[$v] ?? ((string)$v === '' ? '未填写' : (string)$v);
    }

    /**
     * 可选：若你后续添加 title_zh / abstract_zh 字段，页面可优先显示中文。
     */
    public function getDisplayTitle(): string
    {
        // 没有字段也不会报错（hasAttribute 会判断）
        if ($this->hasAttribute('title_zh') && !empty($this->getAttribute('title_zh'))) {
            return (string)$this->getAttribute('title_zh');
        }
        return (string)$this->title;
    }

    public function getDisplayAbstract(): ?string
    {
        if ($this->hasAttribute('abstract_zh') && !empty($this->getAttribute('abstract_zh'))) {
            return (string)$this->getAttribute('abstract_zh');
        }
        return $this->abstract ? (string)$this->abstract : null;
    }

    /**
     * 自动翻译为中文：安全降级，不配置 translator 也不会报错
     */
    protected function tryAutoTranslateToZh(): void
    {
        // 没有中文字段就直接跳过（你现在表结构没有这俩列的话不会做任何事）
        $hasTitleZh = $this->hasAttribute('title_zh');
        $hasAbsZh   = $this->hasAttribute('abstract_zh');
        if (!$hasTitleZh && !$hasAbsZh) {
            return;
        }

        // translator 没配置也直接跳过
        if (!isset(\Yii::$app->translator)) {
            return;
        }

        try {
            // title_zh
            if ($hasTitleZh && empty($this->getAttribute('title_zh')) && !empty($this->title)) {
                $zh = \Yii::$app->translator->translateToZh((string)$this->title);
                if (!empty($zh)) {
                    $this->setAttribute('title_zh', $zh);
                }
            }

            // abstract_zh
            if ($hasAbsZh && empty($this->getAttribute('abstract_zh')) && !empty($this->abstract)) {
                $zh = \Yii::$app->translator->translateToZh((string)$this->abstract);
                if (!empty($zh)) {
                    $this->setAttribute('abstract_zh', $zh);
                }
            }
        } catch (\Throwable $e) {
            // 任何翻译错误都不影响保存
        }
    }
}
