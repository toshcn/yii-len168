<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TermCatesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Term Cates');
$this->params['breadcrumbs'][] = $this->title;
$this->params['contentClass'] = '';
?>
<div class="warpper wrapper-content">
    <div class="row">
        <div class="col-lg-12 backend-head-box">
            <div class="m-b-md border-bottom white-bg dashboard-header">
                <span>文章分类</span>
            </div>
        </div>
        <div class="col-sm-3 backend-left-box">
            <?= $this->render('_categoryForm', [
                'model' => $categoryForm,
            ]) ?>
        </div>

        <div class="col-sm-9 backend-right-box">
            <div class="term-cates-index white-box">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'termid',
                        'title',
                        //'slug',
                        //['label' => '描述', 'value' => 'title'],
                        'parent',
                        'catetype',

                        [
                            'header' => '操作',
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{update}{delete}',
                            'buttons' => [
                                'delete' => function ($url, $model, $key) {
                                    $options = [
                                        'class' => 'pull-right',
                                        'title' => Yii::t('yii', 'Delete'),
                                        'aria-label' => Yii::t('yii', 'Delete'),
                                        'data-pjax' => $model->termid,
                                    ];
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/post/delete-category', 'id' => $model->termid], $options);
                                },
                                'update' => function ($url, $model, $key) {
                                    $options = [
                                        'title' => Yii::t('yii', 'Update'),
                                        'aria-label' => Yii::t('yii', 'Update'),
                                        'data-pjax' => $model->termid,
                                    ];
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['/post/update-category', 'id' => $model->termid], $options);
                                },
                            ],
                        ],
                    ],
                ]); ?>

            </div>
        </div>
    </div>
</div>