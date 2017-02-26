<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TermCatesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Term Cates');
$this->params['breadcrumbs'][] = $this->title;
$this->params['route'] = $route;
$this->params['contentClass'] = '';
?>
<div class="warpper wrapper-content">
    <div class="row">
        <div class="col-sm-3 backend-left-box">
            <?= $this->render('_linkForm', [
                'model' => $linkForm,
            ]) ?>
        </div>

        <div class="col-sm-9 backend-right-box">
            <div class="link-index white-box">
                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        //'termid',
                        'link_title',
                        'link_type',
                        'link_url',
                        'link_icon',
                        //['label' => '描述', 'value' => 'title'],

                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>

            </div>
        </div>
    </div>
</div>