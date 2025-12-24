<?php
/**
 * Team: DBIS_Yii2_Project
 * This is the model class for table "company".
 */

namespace common\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "company".
 *
 * @property int $id 企业ID
 * @property int $team_id 关联队伍ID
 * @property string|null $e_mail 企业邮箱
 * @property string|null $logo Logo图片
 * @property string|null $web 企业网站
 * @property int $display_status 展示状态
 *
 * @property Team $team
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $logoFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['team_id'], 'required'],
            [['team_id', 'display_status'], 'integer'],
            [['e_mail'], 'string', 'max' => 100],
            [['e_mail'], 'email'],
            [['logo', 'web'], 'string', 'max' => 255],
            [['web'], 'url', 'defaultScheme' => 'https'],
            ['display_status', 'default', 'value' => 1],
            [['team_id'], 'exist', 'skipOnError' => true, 'targetClass' => Team::class, 'targetAttribute' => ['team_id' => 'id']],
            [['logoFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'team_id' => '关联队伍',
            'e_mail' => '企业邮箱',
            'logo' => 'Logo图片',
            'web' => '企业官网',
            'display_status' => '展示状态',
            'logoFile' => '上传Logo',
        ];
    }

    /**
     * Gets query for [[Team]].
     */
    public function getTeam()
    {
        return $this->hasOne(Team::class, ['id' => 'team_id']);
    }

    /**
     * 上传Logo
     */
    public function upload()
    {
        if (!$this->validate()) {
            return false;
        }
        
        if ($this->logoFile) {
            $fileName = 'company_logo_' . time() . '_' . rand(100, 999) . '.' . $this->logoFile->extension;
            $uploadPath = Yii::getAlias('@frontend/web/uploads/company/');
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            if ($this->logoFile->saveAs($uploadPath . $fileName)) {
                $this->logo = $fileName;
            }
        }
        
        return true;
    }
}
