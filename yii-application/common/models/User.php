<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model (simplified for your DB schema)
 *
 * Table: user
 * Columns:
 *  - id
 *  - username
 *  - auth_key
 *  - password_hash
 *  - password_reset_token
 *  - created_at
 *  - updated_at
 *  - verification_token
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string|null $verification_token
 * @property int $created_at
 * @property int $updated_at
 */
class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return '{{%user}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class, // 自动维护 created_at / updated_at
        ];
    }

    public function rules()
    {
        return [
            [['username', 'password_hash', 'auth_key'], 'required'],
            [['username'], 'string', 'max' => 255],
            [['password_hash'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['password_reset_token', 'verification_token'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /* ===================== IdentityInterface ===================== */

    public static function findIdentity($id)
    {
        // ✅ 你的表没有 status，直接按 id 找
        return static::findOne(['id' => $id]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public function getId()
    {
        return (int)$this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return (string)$this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === (string)$authKey;
    }

    /* ===================== Finders ===================== */

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne(['password_reset_token' => $token]);
    }

    public static function findByVerificationToken($token)
    {
        // 你现在不一定用到，但留着不影响
        return static::findOne(['verification_token' => $token]);
    }

    /* ===================== Password / Token helpers ===================== */

    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $pos = strrpos($token, '_');
        if ($pos === false) {
            return false;
        }

        $timestamp = (int)substr($token, $pos + 1);
        $expire = (int)(Yii::$app->params['user.passwordResetTokenExpire'] ?? 3600);
        return $timestamp + $expire >= time();
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword((string)$password, (string)$this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash((string)$password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString(32);
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function generateVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
}
