<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the ActiveRecord model for table "players" (选手模型)
 */
namespace common\models;

use Yii;
use yii\web\UploadedFile;

// ...existing code...
class Player extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'players';
    }

    // ==========================================================
    // ★★★ 虚拟属性定义 (用于接收表单上传文件) ★★★
    // ==========================================================
    
    /**
     * @var UploadedFile|null
     */
    public $imageFile; // 接收头像

    /**
     * @var UploadedFile|null
     */
    public $videoFile; // 接收视频

    /**
     * @var UploadedFile|null
     */
    public $coverFile; // 接收视频封面

    // ==========================================================

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // 基础字段验证
            [['name', 'register_name', 'gender'], 'required'],
            [['gender'], 'string'],
            [['team_id', 'org_id', 'display_status'], 'integer'],
            [['join_date'], 'safe'],
            [['name', 'register_name', 'nickname'], 'string', 'max' => 100],
            [['org_rank'], 'string', 'max' => 50],
            
            // 关联验证
            [['org_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['org_id' => 'id']],
            [['team_id'], 'exist', 'skipOnError' => true, 'targetClass' => Team::className(), 'targetAttribute' => ['team_id' => 'id']],
            
            ['display_status', 'default', 'value' => 1],

            // 1. 头像验证规则 (图片, Max 10MB)
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 10],

            // 2. 视频验证规则 (视频, Max 50MB)
            [['videoFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'mp4, webm', 'maxSize' => 1024 * 1024 * 50],

            // 3. 封面验证规则 (图片, Max 5MB)
            [['coverFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '姓名',
            'register_name' => '注册名',
            'gender' => '性别',
            'nickname' => '绰号',
            'team_id' => '所属队伍',
            'org_id' => '所属团体',
            'org_rank' => '团体段位',
            'join_date' => '加入时间',
            'display_status' => '展示状态',
            
            // 表单显示的 Label
            'imageFile' => '上传头像 (Image)',
            'videoFile' => '上传视频 (Video)',
            'coverFile' => '上传封面 (Cover)',
        ];
    }

    // ... (Getters 关联关系保持不变) ...
    public function getPlayerSeasonStats()
    {
        return $this->hasMany(PlayerSeasonStat::className(), ['player_id' => 'id']);
    }

    public function getSeasons()
    {
        return $this->hasMany(Season::className(), ['id' => 'season_id'])->viaTable('player_season_stats', ['player_id' => 'id']);
    }

    public function getOrg()
    {
        return $this->hasOne(Organization::className(), ['id' => 'org_id']);
    }

    public function getTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'team_id']);
    }

    public function getSeasonTitles()
    {
        return $this->hasMany(SeasonTitle::className(), ['player_id' => 'id']);
    }

    /**
     * ★★★ 核心上传方法：同时处理 Avatar, Video, Cover ★★★
     */
    public function upload()
    {
        // 1. 运行验证规则
        if ($this->validate()) {
            
            // --- A. 处理头像 (Avatar) ---
            if ($this->imageFile) {
                $path = \Yii::getAlias('@frontend/web/uploads/players/');
                if (!file_exists($path)) { mkdir($path, 0777, true); }
                
                $fileName = 'player_' . time() . '_' . rand(100, 999) . '.' . $this->imageFile->extension;
                $this->imageFile->saveAs($path . $fileName);
                
                $this->avatar = $fileName; // 赋值给 DB 字段
            }

            // --- B. 处理视频 (Video) ---
            if ($this->videoFile) {
                $videoPath = \Yii::getAlias('@frontend/web/uploads/players/video/');
                if (!file_exists($videoPath)) { mkdir($videoPath, 0777, true); }

                $videoName = 'pv_' . time() . '_' . rand(100, 999) . '.' . $this->videoFile->extension;
                $this->videoFile->saveAs($videoPath . $videoName);
                
                $this->intro_video = $videoName; // 赋值给 DB 字段
            }

            // --- C. 处理封面 (Cover) ---
            if ($this->coverFile) {
                $coverPath = \Yii::getAlias('@frontend/web/uploads/players/cover/');
                if (!file_exists($coverPath)) { mkdir($coverPath, 0777, true); }

                $coverName = 'cover_' . time() . '_' . rand(100, 999) . '.' . $this->coverFile->extension;
                $this->coverFile->saveAs($coverPath . $coverName);
                
                $this->cover = $coverName; // 赋值给 DB 字段
            }

            return true;
        } else {
            return false;
        }
    }
}