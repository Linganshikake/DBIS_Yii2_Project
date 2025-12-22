<?php
/**
 * Team: DBIS_Yii2_Project
 * This is the model class for table "teams".
 */

namespace common\models;
use yii\web\UploadedFile;

use Yii;

/**
 * This is the model class for table "teams".
 *
 * @property int $id 队伍ID
 * @property string $name 队伍名称
 * @property string|null $supervisor 监督/教练姓名
 * @property string|null $company 所属企业
 * @property string|null $description 队伍简介
 * @property int $display_status 展示状态 1:显示 0:隐藏
 * @property string|null $logo Logo图片
 * @property string|null $intro_video 介绍视频
 * @property string|null $video_cover 视频封面
 * @property string|null $supervisor_photo 监督照片
 *
 * @property PlayerSeasonStat[] $playerSeasonStats
 * @property Player[] $players
 * @property TeamSeasonStat[] $teamSeasonStats
 * @property Season[] $seasons
 * @property Company[] $companies
 */
class Team extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'teams';
    }

    /**
     * @var UploadedFile
     */
    public $imageFile; // Logo文件
    
    /**
     * @var UploadedFile
     */
    public $videoFile; // 介绍视频文件
    
    /**
     * @var UploadedFile
     */
    public $coverFile; // 视频封面文件
    
    /**
     * @var UploadedFile
     */
    public $supervisorPhotoFile; // 监督照片文件

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['display_status'], 'integer'],
            ['display_status', 'default', 'value' => 1],
            [['name', 'supervisor', 'company'], 'string', 'max' => 100],
            [['logo', 'intro_video', 'video_cover', 'supervisor_photo'], 'string', 'max' => 255],
            [['name'], 'unique'],

            // Logo验证规则
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 10],
            
            // 视频验证规则
            [['videoFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'mp4, webm', 'maxSize' => 1024 * 1024 * 100],
            
            // 视频封面验证规则
            [['coverFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 5],
            
            // 监督照片验证规则
            [['supervisorPhotoFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '队伍名称',
            'supervisor' => '监督',
            'company' => '所属企业',
            'description' => '队伍简介',
            'display_status' => '展示状态',
            'logo' => 'Logo',
            'intro_video' => '介绍视频',
            'video_cover' => '视频封面',
            'supervisor_photo' => '监督照片',
            'imageFile' => '上传Logo',
            'videoFile' => '上传视频',
            'coverFile' => '上传视频封面',
            'supervisorPhotoFile' => '上传监督照片',
        ];
    }

    /**
     * Gets query for [[PlayerSeasonStats]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlayerSeasonStats()
    {
        return $this->hasMany(PlayerSeasonStat::className(), ['team_id' => 'id']);
    }

    /**
     * Gets query for [[Players]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlayers()
    {
        return $this->hasMany(Player::className(), ['team_id' => 'id']);
    }

    /**
     * Gets query for [[TeamSeasonStats]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeamSeasonStats()
    {
        return $this->hasMany(TeamSeasonStat::className(), ['team_id' => 'id']);
    }

    /**
     * Gets query for [[Seasons]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSeasons()
    {
        return $this->hasMany(Season::className(), ['id' => 'season_id'])->viaTable('team_season_stats', ['team_id' => 'id']);
    }

    /**
     * Gets query for [[Companies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompanies()
    {
        return $this->hasMany(Company::class, ['team_id' => 'id']);
    }

    /**
     * 获取当前赛季成绩
     */
    public function getCurrentSeasonStat()
    {
        $currentSeason = Season::findOne(['is_current' => 1]);
        if ($currentSeason) {
            return TeamSeasonStat::findOne(['team_id' => $this->id, 'season_id' => $currentSeason->id]);
        }
        return null;
    }

    /**
     * ★ 核心功能：上传所有文件
     */
    public function upload()
    {
        if ($this->validate()) {
            $basePath = Yii::getAlias('@frontend/web/uploads/teams/');
            
            // 确保目录存在
            if (!file_exists($basePath)) {
                mkdir($basePath, 0777, true);
            }
            if (!file_exists($basePath . 'video/')) {
                mkdir($basePath . 'video/', 0777, true);
            }
            if (!file_exists($basePath . 'cover/')) {
                mkdir($basePath . 'cover/', 0777, true);
            }
            if (!file_exists($basePath . 'supervisor/')) {
                mkdir($basePath . 'supervisor/', 0777, true);
            }

            // 1. 上传Logo
            if ($this->imageFile) {
                $fileName = 'team_' . time() . '_' . rand(100, 999) . '.' . $this->imageFile->extension;
                $this->imageFile->saveAs($basePath . $fileName);
                $this->logo = $fileName;
            }
            
            // 2. 上传介绍视频
            if ($this->videoFile) {
                $fileName = 'team_video_' . time() . '_' . rand(100, 999) . '.' . $this->videoFile->extension;
                $this->videoFile->saveAs($basePath . 'video/' . $fileName);
                $this->intro_video = $fileName;
            }
            
            // 3. 上传视频封面
            if ($this->coverFile) {
                $fileName = 'team_cover_' . time() . '_' . rand(100, 999) . '.' . $this->coverFile->extension;
                $this->coverFile->saveAs($basePath . 'cover/' . $fileName);
                $this->video_cover = $fileName;
            }
            
            // 4. 上传监督照片
            if ($this->supervisorPhotoFile) {
                $fileName = 'supervisor_' . time() . '_' . rand(100, 999) . '.' . $this->supervisorPhotoFile->extension;
                $this->supervisorPhotoFile->saveAs($basePath . 'supervisor/' . $fileName);
                $this->supervisor_photo = $fileName;
            }
            
            return true;
        } else {
            return false;
        }
    }

}
