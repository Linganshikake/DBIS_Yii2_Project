<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "documents".
 *
 * @property int $id
 * @property string $title
 * @property string $category
 * @property string $year
 * @property string $source
 * @property string $summary
 * @property string $content
 * @property int $created_at
 */
class Documents extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'documents';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'title', 'category', 'year', 'source', 'summary', 'content', 'created_at'], 'required'],
            [['id', 'created_at'], 'integer'],
            [['summary', 'content'], 'string'],
            [['title', 'source'], 'string', 'max' => 255],
            [['category'], 'string', 'max' => 50],
            [['year'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'category' => 'Category',
            'year' => 'Year',
            'source' => 'Source',
            'summary' => 'Summary',
            'content' => 'Content',
            'created_at' => 'Created At',
        ];
    }

}
