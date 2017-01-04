<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Links */

$this->title = Yii::t('link', 'Update {modelClass}: ', [
    'modelClass' => 'Links',
]) . $model->linkid;
$this->params['breadcrumbs'][] = ['label' => Yii::t('link', 'Links'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->linkid, 'url' => ['view', 'id' => $model->linkid]];
$this->params['breadcrumbs'][] = Yii::t('link', 'Update');
?>
<div class="links-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
