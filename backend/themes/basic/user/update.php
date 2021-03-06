<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Yii::t('backend/user', 'Update {modelClass}: ', [
    'modelClass' => 'User',
]) . ' ' . $model->uid;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->uid, 'url' => ['view', 'id' => $model->uid]];
$this->params['breadcrumbs'][] = Yii::t('backend/user', 'Update');
$this->params['route'] = $route;
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
