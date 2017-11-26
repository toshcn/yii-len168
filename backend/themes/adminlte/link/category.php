<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TermCatesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '链接分类列表';
$this->params['breadcrumbs'][] = $this->title;
$this->params['route'] = $route;
$this->params['contentClass'] = '';
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

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'buttons' => [
                                'update' => function ($url, $model, $key) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['/link/update-category', 'id' => $model->termid]);
                                },
                            ],
                        ],
                    ],
                ]); ?>

            </div>
        </div>
    </div>
</div>