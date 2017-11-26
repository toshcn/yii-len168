<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TermCates */

$this->params['breadcrumbs'][] = ['label' => '全部文章', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->termid, 'url' => ['view', 'id' => $model->termid]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="term-cates-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_categoryForm', [
        'model' => $model,
    ]) ?>

</div>
