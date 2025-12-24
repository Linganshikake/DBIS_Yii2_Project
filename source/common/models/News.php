<?php
/**
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the ActiveRecord model for table "news" (新闻模型)
 */

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;

/**
 * This is the model class for table "news".
 *
 * @property int $id 新闻ID
 * @property string $title 新闻标题
 * @property string $publish_time 发布时间
 * @property string $content 新闻正文
 * @property string|null $cover 新闻封面图
 * @property string|null $images 正文图片JSON数组
 * @property int $view_count 浏览次数
 * @property int $is_hot 是否热门
 * @property int $display_status 展示状态
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $coverFile;
    
    /**
     * @var UploadedFile[]
     */
    public $imageFiles;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'content'], 'required'],
            [['content', 'images'], 'string'],
            [['publish_time'], 'safe'],
            [['view_count', 'is_hot', 'display_status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'cover'], 'string', 'max' => 255],
            ['display_status', 'default', 'value' => 1],
            ['is_hot', 'default', 'value' => 0],
            ['view_count', 'default', 'value' => 0],
            
            // 封面图片验证
            [['coverFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif', 'maxSize' => 1024 * 1024 * 5],
            
            // 多张图片验证
            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif', 'maxFiles' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '新闻标题',
            'publish_time' => '发布时间',
            'content' => '新闻正文',
            'cover' => '封面图',
            'images' => '正文图片',
            'view_count' => '浏览次数',
            'is_hot' => '是否热门',
            'display_status' => '展示状态',
            'coverFile' => '上传封面',
            'imageFiles' => '上传图片',
        ];
    }

    /**
     * 上传文件
     */
    public function upload()
    {
        if (!$this->validate()) {
            return false;
        }
        
        // 上传封面
        if ($this->coverFile) {
            $fileName = 'news_cover_' . time() . '_' . rand(100, 999) . '.' . $this->coverFile->extension;
            $uploadPath = Yii::getAlias('@frontend/web/uploads/news/');
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            if ($this->coverFile->saveAs($uploadPath . $fileName)) {
                $this->cover = $fileName;
            }
        }
        
        // 上传多张图片
        if ($this->imageFiles) {
            $imageNames = [];
            $uploadPath = Yii::getAlias('@frontend/web/uploads/news/images/');
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            foreach ($this->imageFiles as $file) {
                $fileName = 'news_img_' . time() . '_' . rand(100, 999) . '.' . $file->extension;
                if ($file->saveAs($uploadPath . $fileName)) {
                    $imageNames[] = $fileName;
                }
            }
            // 合并已有图片
            $existingImages = $this->getImagesArray();
            $this->images = json_encode(array_merge($existingImages, $imageNames));
        }
        
        return true;
    }
    
    /**
     * 获取图片数组
     */
    public function getImagesArray()
    {
        if (empty($this->images)) {
            return [];
        }
        $decoded = json_decode($this->images, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * 获取热门新闻
     */
    public static function getHotNews($limit = 3)
    {
        return self::find()
            ->where(['display_status' => 1])
            ->orderBy(['is_hot' => SORT_DESC, 'view_count' => SORT_DESC, 'publish_time' => SORT_DESC])
            ->limit($limit)
            ->all();
    }
    
    /**
     * 获取最新新闻
     */
    public static function getLatestNews($limit = 10, $offset = 0)
    {
        return self::find()
            ->where(['display_status' => 1])
            ->orderBy(['publish_time' => SORT_DESC])
            ->limit($limit)
            ->offset($offset)
            ->all();
    }
    
    /**
     * 增加浏览次数
     */
    public function incrementViewCount()
    {
        $this->updateCounters(['view_count' => 1]);
    }
}
