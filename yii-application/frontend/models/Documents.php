<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class Documents extends ActiveRecord
{
    public static function tableName()
    {
        return 'documents';
    }
}
