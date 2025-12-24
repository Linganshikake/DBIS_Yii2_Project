<?php
use yii\helpers\Html;

$this->title = '新增文献';
$this->params['breadcrumbs'][] = ['label' => '文献管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>
<?= $this->render('_form', ['model' => $model]) ?>
