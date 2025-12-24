<?php

namespace common\models;

use yii\db\ActiveRecord;

class Keyword extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'keywords';
    }

    public function rules(): array
    {
        return [
            [['keyword'], 'required'],
            [['keyword'], 'string', 'max' => 128],
            [['keyword'], 'unique'],
        ];
    }
}
