<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TermCatesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Terms');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="term-cates-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Terms'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'termid',
            'title',
            'slug',
            'catetype',
            'description:ntext' ,
            //['label' => '描述', 'value' => 'title'],
            'parent',
            // 'counts',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
