<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->uid;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->uid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->uid], [
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
            'uid',
            'group',
            'username',
            'nickname',
            'email:email',
            'head',
            'mobile',
            'sex',
            'auth_key',
            'password',
            'reset_token',
            'reset_token_expire',
            'motto',
            'hp',
            'golds',
            'crystal',
            'posts',
            'comments',
            'friends',
            'followers',
            'os',
            'browser',
            'isauth',
            'status',
            'safe_level',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
