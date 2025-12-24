<?php

namespace common\models;

use yii\db\ActiveRecord;

class WorkAuthor extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'work_authors';
    }

    public function rules(): array
    {
        return [
            [['work_id', 'author_id', 'author_order'], 'required'],
            [['work_id', 'author_id', 'author_order'], 'integer'],
            [['role'], 'string', 'max' => 32],
        ];
    }
}
