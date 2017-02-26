<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\PostsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Posts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="posts-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Posts', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'postid',
            'user_id',
            'title',
            'author',
            'categorys',
            // 'image',
            // 'image_suffix',
            // 'content:ntext',
            // 'content_len',
            // 'description',
            // 'original_url:url',
            // 'copyright',
            // 'spend',
            // 'paytype',
            // 'posttype',
            // 'parent',
            // 'status',
            // 'islock',
            // 'iscomment',
            // 'isstick',
            // 'isnice',
            // 'isopen',
            // 'ispay',
            // 'isforever',
            // 'isdie',
            // 'os',
            // 'browser',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
