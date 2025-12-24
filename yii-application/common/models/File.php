<?php

namespace common\models;

use yii\db\ActiveRecord;

class File extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'files';
    }

    public function rules(): array
    {
        return [
            [['work_id', 'file_type', 'url'], 'required'],
            [['work_id'], 'integer'],
            [['url'], 'string'],
            [['file_type'], 'string', 'max' => 32],
            [['label'], 'string', 'max' => 256],
            [['license'], 'string', 'max' => 128],
        ];
    }
}
