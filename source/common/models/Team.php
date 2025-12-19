<?php
/**
 * Team: DBIS_Yii2_Project (您的团队名称)
 * Coding by: 您的姓名 (您的学号), 2025xxxx (日期)
 * This is the model class for table "此处填表名".
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
 *
 * @property PlayerSeasonStats[] $playerSeasonStats
 * @property Players[] $players
 * @property TeamSeasonStats[] $teamSeasonStats
 * @property Seasons[] $seasons
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
    public $imageFile; // ★ 定义虚拟属性，用于接收表单文件

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
            [['name'], 'unique'],

            // ★ 新增：图片验证规则
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 10], // 限制10MB
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
            'display_status' => 'Display Status',
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
     * ★ 核心功能：上传图片
     * 返回 true 表示成功，false 表示失败
     */
    public function upload()
    {
        if ($this->validate()) {
            if ($this->imageFile) {
                // 1. 确定存储路径：存到 frontend/web/uploads/teams/ 目录下
                // 这样前台页面才能直接用 http://.../uploads/... 访问到
                $path = Yii::getAlias('@frontend/web/uploads/teams/');
                
                // 如果目录不存在，创建它
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                // 2. 生成随机文件名 (防止重名)
                $fileName = 'team_' . time() . '_' . rand(100, 999) . '.' . $this->imageFile->extension;

                // 3. 保存文件
                $this->imageFile->saveAs($path . $fileName);

                // 4. 把文件名赋值给数据库字段
                $this->logo = $fileName;
            }
            return true;
        } else {
            return false;
        }
    }

}
