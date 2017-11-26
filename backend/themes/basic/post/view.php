<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Posts */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="posts-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->postid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->postid], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'postid',
            'user_id',
            'title',
            'author',
            'categorys',
            'image',
            'image_suffix',
            'content:ntext',
            'content_len',
            'description',
            'original_url:url',
            'copyright',
            'spend',
            'paytype',
            'posttype',
            'parent',
            'status',
            'islock',
            'iscomment',
            'isstick',
            'isnice',
            'isopen',
            'ispay',
            'isforever',
            'isdie',
            'os',
            'browser',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
