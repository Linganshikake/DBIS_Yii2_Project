<?php

namespace common\models;

use yii\db\ActiveRecord;

class Author extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'authors';
    }

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name', 'name_alt'], 'string', 'max' => 256],
            [['orcid'], 'string', 'max' => 32],
            [['openalex_id'], 'string', 'max' => 128],
        ];
    }

    public function getWorkAuthors()
    {
        return $this->hasMany(WorkAuthor::class, ['author_id' => 'id']);
    }

    public function getWorks()
    {
        return $this->hasMany(Work::class, ['id' => 'work_id'])
            ->via('workAuthors');
    }
}
