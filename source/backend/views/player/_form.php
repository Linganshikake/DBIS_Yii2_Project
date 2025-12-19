<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Team;
use yii\helpers\ArrayHelper;
use common\models\Organization;

/* @var $this yii\web\View */
/* @var $model common\models\Player */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="player-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'register_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gender')->dropDownList([ '男' => '男', '女' => '女', ], ['prompt' => '请选择性别...']) ?>

    <?= $form->field($model, 'nickname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'team_id')->dropDownList(
        ArrayHelper::map(Team::find()->where(['display_status' => 1])->all(), 'id', 'name'),
        ['prompt' => '请选择所属队伍...']
    ) ?>

    <?= $form->field($model, 'org_id')->dropDownList(
        ArrayHelper::map(Organization::find()->where(['display_status' => 1])->all(), 'id', 'name'),
        ['prompt' => '请选择所属团体...']
    ) ?>

    <?= $form->field($model, 'org_rank')->dropDownList(
        [
            'A1' => 'A1', 'A2' => 'A2', 'B1' => 'B1', 'B2' => 'B2', 
            'C1' => 'C1', 'C2' => 'C2', 'C3' => 'C3',
            'D1' => 'D1', 'D2' => 'D2', 'D3' => 'D3',
            '最高位' => '最高位', '令昭位' => '令昭位', '雀王' => '雀王',
            '凤凰位' => '凤凰位', '将王' => '将王', '女流最高位' => '女流最高位',
            'μ1' => 'μ1', 'μ2' => 'μ2',
        ], 
        ['prompt' => '请选择段位...']
    ) ?>

    <hr style="border-color: #eee; margin-top: 30px;">
    
    <?= $form->field($model, 'imageFile')->fileInput() ?>

    <?php if ($model->avatar): ?>
        <div class="form-group">
            <label>当前头像 (Current Avatar):</label><br>
            <img src="/uploads/players/<?= $model->avatar ?>" width="150" style="border: 1px solid #ccc; padding: 5px; background: #f9f9f9;">
        </div>
    <?php endif; ?>

    <hr style="border-color: #eee; margin-top: 30px;">
    
    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; border: 1px solid #ddd;">
        <h4 style="margin-top: 0; color: #555;">自我介绍视频设置</h4>
        
        <?= $form->field($model, 'videoFile')->fileInput() ?>

        <?php if ($model->intro_video): ?>
            <div class="form-group">
                <label>当前视频 (Current Video):</label><br>
                <video width="320" height="240" controls style="background: #000; border-radius: 4px;">
                    <source src="/uploads/players/video/<?= $model->intro_video ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        <?php endif; ?>

        <div style="margin: 20px 0; border-top: 1px dashed #ccc;"></div>

        <?= $form->field($model, 'coverFile')->fileInput() ?>

        <?php if ($model->cover): ?>
            <div class="form-group">
                <label>当前封面 (Current Cover):</label><br>
                <img src="/uploads/players/cover/<?= $model->cover ?>" width="320" style="border: 1px solid #ccc; padding: 5px; background: #fff;">
            </div>
        <?php endif; ?>
    </div>

    <hr style="border-color: #eee; margin-top: 30px;">

    <?= $form->field($model, 'join_date')->input('date', ['class' => 'form-control']) ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>