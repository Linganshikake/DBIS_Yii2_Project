<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

class SignupForm extends Model
{
    public $username;
    public $password;

    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'This username has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => (int)(Yii::$app->params['user.passwordMinLength'] ?? 6)],
        ];
    }

    /**
     * @return bool|null
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        // 可选：启用邮箱验证再开
        // $user->generateVerificationToken();

        if ($user->save()) {
            return $user; // ✅ 关键：返回身份对象
        }

        return null;
    }

}
