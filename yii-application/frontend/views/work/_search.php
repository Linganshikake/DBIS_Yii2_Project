<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'method' => 'get',
    'options' => ['class' => 'row g-2 align-items-end'],
]);
?>

<?php
use common\models\Work;

/** @var frontend\models\WorkSearch $model */
?>


<div class="col-12 col-md-6">
  <?= Html::label('关键词', 'global', ['class'=>'form-label']) ?>
  <?= Html::textInput('WorkSearch[global]', $model->global ?? '', [
      'class'=>'form-control',
      'placeholder'=>'标题/摘要/来源/DOI',
      'id'=>'global'
  ]) ?>
</div>

<div class="col-6 col-md-2">
    <label class="form-label">类型</label>
    <?= Html::activeDropDownList(
        $model,
        'work_type',
        Work::workTypeMap(),
        [
            'class' => 'form-select',
            'prompt' => '全部类型',
        ]
    ) ?>
</div>



<?php
// 年份下拉：最近 20 年 + 关键年份（你也可以按需要增删）
$yearOptions = [];
$cur = (int)date('Y');
for ($y = $cur; $y >= $cur - 20; $y--) {
    $yearOptions[$y] = (string)$y;
}
// 关键历史年份补充（示例，可删）
foreach ([1945, 1937, 1931, 1941, 1949] as $ky) {
    $yearOptions[$ky] = (string)$ky;
}
krsort($yearOptions);
?>

<div class="col-6 col-md-2">
    <label class="form-label">年份</label>
    <?= Html::activeDropDownList(
        $model,
        'publication_year',
        $yearOptions,
        [
            'class' => 'form-select',
            'prompt' => '全部年份',
        ]
    ) ?>
</div>



<div class="col-12 mt-2">
  <?= Html::submitButton('开始检索', ['class'=>'btn btn-success me-2']) ?>
  <?= Html::a('清空条件', ['index'], ['class'=>'btn btn-outline-secondary']) ?>
</div>

<?php ActiveForm::end(); ?>
