<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Yii::t('backend/user', 'Create User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['route'] = $route;
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_createForm', [
        'model' => $model,
    ]) ?>

</div>
