<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use common\models\User;

/* @var $this yii\web\View */
$this->title = '修改个人资料';
$this->params['bodyClass'] = 'gray-bg';

?>
<div class="wrapper user-wrapper w-center">
    <div class="row">
        <div class="col-md-3">
            <!-- myUserInfoView -->
            <?= $this->render('_myUserInfoView', ['userDetail' => $userDetail]); ?>

            <!-- _myCenterLeftMenuView -->
            <?= $this->render('_myCenterLeftMenuView'); ?>

        </div>
        <div class="col-md-9">
            <div class="user-center">
                <div class="ibox-title">
                    <h5>个人资料</h5>
                </div>
                <div class="ibox">
                    <div class="ibox-content editinfo">
                        <?php $form = ActiveForm::begin([
                            'id' => 'form',
                            'method' => 'post',
                            'options' => ['class' => 'form-horizontal'],
                            'fieldConfig' => [
                                'template' => '{input}{error}',
                            ]
                        ]); ?>
                        <dl>
                            <dd><span>用户名</span><em>&nbsp;</em><?= $userDetail['username'] ?></dd>
                            <dd><span>昵称</span><em>*</em>
                                <?= $form->field($model, 'nickname')->input('text', ['class' => 'form-control input-sm', 'maxlength' => 15, 'value' => $userDetail['nickname']]) ?>
                            </dd>
                            <dd><span>真实姓名</span><em>*</em>
                                <?= $form->field($model, 'realname')->input('text', ['class' => 'form-control input-sm', 'maxlength' => 12, 'value' => $userDetail['userInfo']['realname']]) ?>
                            </dd>
                            <dd><span>手机</span>
                                <?= $form->field($model, 'mobile')->input('text', ['class' => 'form-control input-sm noindex', 'maxlength' => 11, 'value' => $userDetail['userInfo']['mobile']]) ?>
                            </dd>
                            <dd>
                                <span>性别</span>
                                <?= $form->field($model, 'sex')->dropDownList([
                                        '-1' => '保密',
                                        '1' => '男',
                                        '0' => '女'
                                    ]);
                                ?>
                            </dd>
                            <dd>
                                <span>生日</span>
                                <select class="form-control" id="birthday-year">
                                </select>
                                <select class="form-control" id="birthday-month">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                </select>
                                <select class="form-control" id="birthday-day">
                                </select>
                                <?= $form->field($model, 'birthday', ['template' => '{input}'])->input('hidden', ['value' => $userDetail['userInfo']['birthday']]) ?>
                            </dd>
                            <dd><span>身份证号</span>
                                <?= $form->field($model, 'idcode')->input('text', ['class' => 'form-control input-sm noindex', 'maxlength' => 20, 'placeholder' => '身份证号前20位', 'value' => $userDetail['userInfo']['idcode']]) ?>
                            </dd>

                            <dd><span>个人签名</span><em>&nbsp;</em>
                                <?= $form->field($model, 'motto')->textarea(['class' => 'form-control', 'value' => $userDetail['motto']]) ?>
                            <dd><span>&nbsp;</span><em>&nbsp;</em>
                                <?= Html::submitButton('保存', ['class'=> 'btn btn-warning submit']) ;?>
                            </dd>
                        </dd>
                        </dl>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php \common\widgets\JsBlock::begin(); ?>
<script>
    $(document).ready(function(){
        createYearOption(1900);
        $('#userinfoform-sex').val("<?= $userDetail['sex'] ?>");
        var birthday = "<?= $userDetail['userInfo']['birthday'] ?>";
        if (birthday) {
            var birthday = birthday.split('-')
            var day = new Date(birthday[0], birthday[1]-1, birthday[2]);
            $('#birthday-year').val(day.getFullYear());
            $('#birthday-month').val(day.getMonth()+1);
            createDayOption(day.getDate());
        } else {
            createDayOption(1);
        }

        $('#birthday-month').on('change.month', function() {
            createDayOption($('#birthday-day').val());
        });
        $('#birthday-year, #birthday-month, #birthday-day').on('change.birthday', function() {
            var birthday = $('#birthday-year').val() + '-' + $('#birthday-month').val() + '-' + $('#birthday-day').val();
            $('#userinfoform-birthday').val(birthday);
        });
        <?php if($success) { ?>
            swal({title: '提示', text: '已保存成功', type: "success", confirmButtonText: "确定"});
        <?php } ?>

    });
    function createYearOption(yearStart) {
        var day = new Date();
        var nowYear = parseInt(day.getFullYear());
        var yearHtml = '';
        do {
            yearHtml += '<option value="'+nowYear+'">'+nowYear+'</option>';
            nowYear--;
        } while(yearStart <= nowYear);
        $('#birthday-year').html(yearHtml);
    }

    function createDayOption(day) {
        var nowDay = new Date();
        var dayObj = new Date($('#birthday-year').val(), $('#birthday-month').val(), 0);
        var nowYear = parseInt(nowDay.getDate());
        var dayHtml = '';
        var counts = dayObj.getDate();
        for (var i = 1; i <= counts; i++) {
            var selected = day == i ? 'selected' : '';
            dayHtml += '<option value="'+i+'" '+selected+'>'+i+'</option>';
        }
        $('#birthday-day').html(dayHtml);
    }
</script>
<?php \common\widgets\JsBlock::end();?>