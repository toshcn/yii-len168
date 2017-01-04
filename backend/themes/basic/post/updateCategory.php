<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TermCates */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Term Cates',
]) . ' ' . $model->termid;
$this->params['route'] = $route;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Term Cates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->termid, 'url' => ['view', 'id' => $model->termid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="term-cates-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_categoryForm', [
        'model' => $model,
    ]) ?>

</div>
