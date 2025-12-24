<?php

namespace common\models;

use yii\db\ActiveRecord;

class WorkKeyword extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'work_keywords';
    }

    public function rules(): array
    {
        return [
            [['work_id', 'keyword_id'], 'required'],
            [['work_id', 'keyword_id'], 'integer'],
        ];
    }
}
