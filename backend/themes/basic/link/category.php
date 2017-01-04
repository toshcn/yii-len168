<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TermCatesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Term Cates');
$this->params['breadcrumbs'][] = $this->title;
$this->params['route'] = $route;
?>
<div class="warpper wrapper-content">
    <div class="row">
        <div class="col-sm-3 backend-left-box">
            <?= $this->render('_categoryForm', [
                'model' => $categoryForm,
            ]) ?>
        </div>

        <div class="col-sm-9 backend-right-box">
            <div class="term-cates-index white-box">
                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                <p>
                    <?= Html::a(Yii::t('backend/link', 'Create Link'), ['category'], ['class' => 'btn btn-success']) ?>
                </p>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        //'termid',
                        'title',
                        'slug',
                        'catetype',
                        //['label' => '描述', 'value' => 'title'],
                        'parent',

                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>

            </div>
        </div>
    </div>
</div>