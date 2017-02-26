<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use common\models\User;
use common\widgets\JsBlock;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <div class="header-tools">
        <button type="button" id="status-btn" class="btn btn-danger">状态</button>
    </div>

    <?= GridView::widget([
        'id' => 'grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'uid',
            [
                'attribute' => 'group',
                'filter' => Html::activeDropDownList($searchModel, 'group', User::getGroupMap(), ['class' => 'form-control']),
                'value' => function ($data) {
                    return User::getGroupMap()[$data->group];
                },
            ],
            'username',
            'nickname',
            'email:email',
            [
                'attribute' => 'sex',
                'filter' => Html::activeDropDownList($searchModel, 'sex', User::getSexMap(), ['class' => 'form-control']),
                'value' => function ($data) {
                    return User::getSexMap()[$data->sex];
                },
            ],
            [
                'attribute' => 'status',
                'filter' => Html::activeDropDownList($searchModel, 'status', User::getStatusMap(), ['class' => 'form-control']),
                'value' => function ($data) {
                    return User::getStatusMap()[$data->status];
                },
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
</div>

<div class="modal fade" id="status-modal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="status-modal-content">
            <?php $form = ActiveForm::begin([
                'id' => 'status-form',
                'action' => Url::to(['/user/ajax-status']),
                'options' => ['class' => 'form-horizontal']
            ]); ?>
            <div class="modal-header">
                <a href="javascript:;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </a>
                <h4 class="modal-title text-warning"><b>状态</b></h4>
            </div>
            <div class="modal-body text-center" id="status-modal-body">
            <div class="form-controll">
                <div class="form-group">
                    <label class="col-md-3 control-label">状态</label>
                    <div class="col-md-9">
                        <?= Html::dropDownList('status', null, User::getStatusMap(), ['class' => 'form-control']); ?>
                    </div>
                </div>
            </div>
            </div>

            <div class="modal-footer">
                <div class="text-center">
                    <button type="button" class="btn btn-info" id="status-save-btn">确定</button>
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
        $('#status-btn').on('click', function() {
            $('#status-modal').modal('show');
        });
        $('#status-save-btn').on('click', function() {
            $('#status-modal').modal('hide');
            var keys = $('#grid').yiiGridView('getSelectedRows');
            if(keys.length == 0) {
                $('#error-modal-body').html('<p class="text-danger">请选择操作的会员!</p>');
                $('#error-modal').modal('show');
            } else {
                $.post($('#status-form').prop('action'), {"status": $('[name="status"]').val(), "id[]": keys}, function(json) {
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
