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

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'register_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gender')->dropDownList([ '男' => '男', '女' => '女', ], ['prompt' => '请选择性别...']) ?>

    <?= $form->field($model, 'nickname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'team_id')->dropDownList(
    // 会自动从 teams 表里读取所有 ID 和 Name，生成数组
    ArrayHelper::map(Team::find()->where(['display_status' => 1])->all(), 'id', 'name'),
    ['prompt' => '请选择所属队伍...']
    ) ?>

    <?= $form->field($model, 'org_id')->dropDownList(
    // 读取所有状态为显示的团体，生成 [id => name] 的数组
    ArrayHelper::map(Organization::find()->where(['display_status' => 1])->all(), 'id', 'name'),
    ['prompt' => '请选择所属团体...']
    ) ?>

    <?= $form->field($model, 'org_rank')->dropDownList(
    [
        'A1' => 'A1', 
        'A2' => 'A2', 
        'B1' => 'B1', 
        'B2' => 'B2', 
        'C1' => 'C1', 
        'C2' => 'C2', 
        'C3' => 'C3',
        'D1' => 'D1',
        'D2' => 'D2',
        'D3' => 'D3',
        '最高位' => '最高位',
        '令昭位' => '令昭位',
        '雀王' => '雀王',
        '凤凰位' => '凤凰位',
        '将王' => '将王',
        '女流最高位' => '女流最高位',
        'μ1' => 'μ1',
        'μ2' => 'μ2',
    ], 
    ['prompt' => '请选择段位...'] // 注意：下拉菜单通常只能选择，不能输入。如果段位太杂，这里可能列举不全。
    ) ?>

    <?= $form->field($model, 'join_date')->input('date', ['class' => 'form-control']) ?>


    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
