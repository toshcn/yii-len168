<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Links */

$this->title = $model->linkid;
$this->params['breadcrumbs'][] = ['label' => Yii::t('link', 'Links'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="links-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('link', 'Update'), ['update', 'id' => $model->linkid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('link', 'Delete'), ['delete', 'id' => $model->linkid], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('link', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'linkid',
            'link_title',
            'link_type',
            'link_url:url',
            'link_icon',
            'link_sort',
        ],
    ]) ?>

</div>
