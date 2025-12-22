<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$this->title = '编辑个人资料';
?>

<div class="profile-update">

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card" style="background: #1a1a1a; border: 1px solid #333; border-radius: 10px;">
                <div class="card-header" style="background: #222; border-bottom: 1px solid #333; padding: 20px;">
                    <h4 style="color: #d4af37; margin: 0; font-weight: bold;">
                        <i class="fa fa-edit"></i> 编辑个人资料
                    </h4>
                </div>
                <div class="card-body" style="padding: 30px;">
                    
                    <?php $form = ActiveForm::begin([
                        'options' => ['enctype' => 'multipart/form-data'],
                    ]); ?>
                    
                    <!-- 头像预览 -->
                    <div class="form-group text-center mb-4">
                        <div style="width: 100px; height: 100px; border-radius: 50%; overflow: hidden; border: 3px solid #d4af37; margin: 0 auto 15px;">
                            <?php if ($user->avatar): ?>
                                <img src="/uploads/avatars/<?= $user->avatar ?>" style="width: 100%; height: 100%; object-fit: cover;" id="avatar-preview">
                            <?php else: ?>
                                <div style="width: 100%; height: 100%; background: #333; display: flex; align-items: center; justify-content: center;" id="avatar-placeholder">
                                    <i class="fa fa-user" style="font-size: 40px; color: #666;"></i>
                                </div>
                                <img src="" style="width: 100%; height: 100%; object-fit: cover; display: none;" id="avatar-preview">
                            <?php endif; ?>
                        </div>
                        <?= $form->field($user, 'avatarFile')->fileInput([
                            'class' => 'form-control',
                            'accept' => 'image/*',
                            'onchange' => 'previewAvatar(this)',
                        ])->label('更换头像') ?>
                    </div>
                    
                    <!-- 昵称 -->
                    <?= $form->field($user, 'nickname')->textInput([
                        'maxlength' => true,
                        'class' => 'form-control',
                        'style' => 'background: #222; border: 1px solid #333; color: #fff;',
                        'placeholder' => '设置一个昵称',
                    ])->label('昵称') ?>
                    
                    <!-- 个人简介 -->
                    <?= $form->field($user, 'bio')->textarea([
                        'rows' => 4,
                        'class' => 'form-control',
                        'style' => 'background: #222; border: 1px solid #333; color: #fff;',
                        'placeholder' => '介绍一下你自己...',
                    ])->label('个人简介') ?>
                    
                    <!-- 邮箱（只读） -->
                    <div class="form-group">
                        <label style="color: #888;">邮箱</label>
                        <input type="text" class="form-control" value="<?= Html::encode($user->email) ?>" 
                               style="background: #333; border: 1px solid #333; color: #666;" readonly>
                        <small class="form-text text-muted">邮箱不可修改</small>
                    </div>
                    
                    <div class="form-group mt-4">
                        <?= Html::submitButton('保存修改', ['class' => 'btn btn-warning btn-block', 'style' => 'font-weight: bold;']) ?>
                    </div>
                    
                    <?php ActiveForm::end(); ?>
                    
                    <div class="text-center mt-3">
                        <a href="<?= \yii\helpers\Url::to(['profile/index']) ?>" style="color: #888;">
                            <i class="fa fa-arrow-left"></i> 返回个人主页
                        </a>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById('avatar-preview');
            var placeholder = document.getElementById('avatar-placeholder');
            preview.src = e.target.result;
            preview.style.display = 'block';
            if (placeholder) placeholder.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<style>
.profile-update .form-control:focus {
    background: #222;
    border-color: #d4af37;
    color: #fff;
    box-shadow: none;
}
.profile-update label {
    color: #888;
}
</style>
