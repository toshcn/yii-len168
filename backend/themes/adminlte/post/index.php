<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use common\models\Posts;
use common\widgets\JsBlock;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PostsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '全部文章';
$this->params['breadcrumbs'][] = $this->title;
$this->params['contentClass'] = 'white-bg';
?>
<div class="posts-index">
    <div class="header-tools">
        <button type="button" id="stick-btn" class="btn btn-warning">置顶</button>
        <button type="button" id="nice-btn" class="btn btn-info">推荐</button>
        <button type="button" id="lock-btn" class="btn btn-danger">锁定</button>
    </div>

<?php Pjax::begin(); ?>
    <?= GridView::widget([
        'id' => 'grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'postid',
            'user_id',
            [
                'class' => 'kartik\grid\DataColumn',
                'format' => 'html',
                'attribute' => 'title',
                'value' => function ($data) {
                    return Html::a($data->title, Yii::$app->urlManager->createUrl(['/article/detail', 'id' => $data->postid], 'home'), ['target' => '_blank']);
                }
            ],
            [
                'label' => '状态',
                'attribute' => 'status',
                'filter' => Html::activeDropDownList($searchModel, 'status', Posts::getStatusMap(), ['class' => 'form-control']),
                'value' => function ($data) {
                    return Posts::getStatusMap()[$data->status];
                },
            ],
            [
                'class' => 'kartik\grid\BooleanColumn',
                'attribute' => 'isstick',
                'trueLabel' => '是',
                'falseLabel' => '否'
            ],
            [
                'class' => 'kartik\grid\BooleanColumn',
                'attribute' => 'isnice',
                'trueLabel' => '是',
                'falseLabel' => '否'
            ],
            [
                'class' => 'kartik\grid\BooleanColumn',
                'label' => '锁定',
                'attribute' => 'islock',
                'trueLabel' => '是',
                'falseLabel' => '否'
            ],
            [
                'class' => 'kartik\grid\BooleanColumn',
                'label' => '评论',
                'attribute' => 'iscomment',
                'trueLabel' => '开放',
                'falseLabel' => '关闭'
            ],
            [
                'class' => 'kartik\grid\BooleanColumn',
                'label' => '公开',
                'attribute' => 'isopen',
                'trueLabel' => '是',
                'falseLabel' => '否'
            ],
            [
                'class' => 'kartik\grid\BooleanColumn',
                'attribute' => 'isdie',
                'trueLabel' => '是',
                'falseLabel' => '否'
            ],
            [
                'class' => \yii\grid\DataColumn::className(), // this line is optional
                'attribute' => 'created_at',
                'format' => ['date', 'php:Y-m-d'],
                'label' => '时间',
            ],
            ['class' => 'yii\grid\CheckboxColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?>
</div>
<div class="modal fade" id="stick-modal" tabindex="-1" role="dialog" aria-labelledby="stickModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="stick-modal-content">
            <?php $form = ActiveForm::begin([
                'id' => 'stick-form',
                'action' => Url::to(['/post/ajax-stick']),
                'options' => ['class' => 'form-horizontal']
            ]); ?>
            <div class="modal-header">
                <a href="javascript:;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </a>
                <h4 class="modal-title text-warning"><b>置顶</b></h4>
            </div>
            <div class="modal-body text-center" id="stick-modal-body">
            <div class="form-controll">
                <div class="form-group">
                    <label class="col-md-3 control-label">置顶</label>
                    <div class="col-md-9">
                        <label class="col-md-6 radio"><input type="radio" name="stick" value="1" checked> 是</label>
                        <label class="col-md-6 radio"><input type="radio" name="stick" value="0"> 否</label>
                    </div>
                </div>
            </div>
            </div>

            <div class="modal-footer">
                <div class="text-center">
                    <button type="button" class="btn btn-info" id="stick-save-btn">确定</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="nice-modal" tabindex="-1" role="dialog" aria-labelledby="niceModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="nice-modal-content">
            <?php $form = ActiveForm::begin([
                'id' => 'nice-form',
                'action' => Url::to(['/post/ajax-nice']),
                'options' => ['class' => 'form-horizontal']
            ]); ?>
            <div class="modal-header">
                <a href="javascript:;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </a>
                <h4 class="modal-title text-warning"><b>推荐</b></h4>
            </div>
            <div class="modal-body text-center" id="nice-modal-body">
            <div class="form-controll">
                <div class="form-group">
                    <label class="col-md-3 control-label">推荐</label>
                    <div class="col-md-9">
                        <label class="col-md-6 radio"><input type="radio" name="nice" value="1" checked> 是</label>
                        <label class="col-md-6 radio"><input type="radio" name="nice" value="0"> 否</label>
                    </div>
                </div>
            </div>
            </div>

            <div class="modal-footer">
                <div class="text-center">
                    <button type="button" class="btn btn-info" id="nice-save-btn">确定</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="lock-modal" tabindex="-1" role="dialog" aria-labelledby="lockModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="lock-modal-content">
            <?php $form = ActiveForm::begin([
                'id' => 'lock-form',
                'action' => Url::to(['/post/ajax-lock']),
                'options' => ['class' => 'form-horizontal']
            ]); ?>
            <div class="modal-header">
                <a href="javascript:;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </a>
                <h4 class="modal-title text-warning"><b>锁定</b></h4>
            </div>
            <div class="modal-body text-center" id="lock-modal-body">
            <div class="form-controll">
                <div class="form-group">
                    <label class="col-md-3 control-label">锁定文章</label>
                    <div class="col-md-9">
                        <label class="col-md-6 radio"><input type="radio" name="lock" value="1" checked> 锁定</label>
                        <label class="col-md-6 radio"><input type="radio" name="lock" value="0"> 解锁</label>
                    </div>
                </div>
            </div>
            </div>

            <div class="modal-footer">
                <div class="text-center">
                    <button type="button" class="btn btn-info" id="lock-save-btn">确定</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?php JsBlock::begin(); ?>
<script>
    jQuery(function($) {
        $('#stick-btn').on('click', function() {
            $('#stick-modal').modal('show');
        });
        $('#stick-save-btn').on('click', function() {
            $('#stick-modal').modal('hide');
            var keys = $('#grid').yiiGridView('getSelectedRows');
            if(keys.length == 0) {
                $('#error-modal-body').html('<p class="text-danger">请选择操作的文章!</p>');
                $('#error-modal').modal('show');
            } else {
                $.post($('#stick-form').prop('action'), {"stick": $('[name="stick"]:checked').val(), "id[]": keys}, function(json) {
                    if (json.ok) {
                        window.location.reload();
                    } else {
                        $('#error-modal-body').html('<p class="text-danger">操作失败!</p>');
                        $('#error-modal').modal('show');
                    }
                });
            }
        });

        $('#nice-btn').on('click', function() {
            $('#nice-modal').modal('show');
        });
        $('#nice-save-btn').on('click', function() {
            $('#nice-modal').modal('hide');
            var keys = $('#grid').yiiGridView('getSelectedRows');
            if(keys.length == 0) {
                $('#error-modal-body').html('<p class="text-danger">请选择操作的文章!</p>');
                $('#error-modal').modal('show');
            } else {
                $.post($('#nice-form').prop('action'), {"nice": $('[name="nice"]:checked').val(), "id[]": keys}, function(json) {
                    if (json.ok) {
                        window.location.reload();
                    } else {
                        $('#error-modal-body').html('<p class="text-danger">操作失败!</p>');
                        $('#error-modal').modal('show');
                    }
                });
            }
        });

        $('#lock-btn').on('click', function() {
            $('#lock-modal').modal('show');
        });
        $('#lock-save-btn').on('click', function() {
            $('#lock-modal').modal('hide');
            var keys = $('#grid').yiiGridView('getSelectedRows');
            if(keys.length == 0) {
                $('#error-modal-body').html('<p class="text-danger">请选择操作的文章!</p>');
                $('#error-modal').modal('show');
            } else {
                $.post($('#lock-form').prop('action'), {"lock": $('[name="lock"]:checked').val(), "id[]": keys}, function(json) {
                    if (json.ok) {
                        window.location.reload();
                    } else {
                        $('#error-modal-body').html('<p class="text-danger">操作失败!</p>');
                        $('#error-modal').modal('show');
                    }
                });
            }
        });

    });
</script>
<?php JsBlock::end(); ?>
