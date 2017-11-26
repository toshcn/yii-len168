<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use common\models\User;

/* @var $this yii\web\View */
$this->title = '帐号绑定';
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
                <div class="ibox">
                    <div class="ibox-title">
                        <h5><?= $this->title ?></h5>
                    </div>
                    <div class="ibox-content center-post-list">
                        <div class="col-md-12">
                            <table class="table">
                                <col width="35%" />
                                <col width="20%" />
                                <col width="25%" />
                                <col width="10%" />
                                <thead>
                                    <tr>
                                        <th>第三方</th>
                                        <th>昵称</th>
                                        <th>日期</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($auth as $key => $item) { ?>
                                        <tr>
                                            <td>
                                                <?= Html::encode($item['source']) ?></a>
                                            </td>
                                            <td><?= Html::encode($item['nickname']) ?></td>
                                            <td><?= $item['created_at'] ?></td>
                                            <td>
                                                <button name="unlink-oauth" type="button" class="btn btn btn-xs btn-success" data-id="<?= $item['id']?>">解绑</button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="ibox-footer">
                        <h4>您可以绑定以下第三方帐号：</h4>
                        <p class="oauth-list">
                            <a href="<?= Url::to(['/ucenter/auth/login', 'authclient' => 'github'], true)?>" title="Github"><i class="fa fa-github icon-github"></i></a>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php \common\widgets\JsBlock::begin(); ?>
<script>
    $(document).ready(function(){
        $('[name="unlink-oauth"]').on('click', function() {
            var id = $(this).attr('data-id');
            $.post("<?= Url::to(['/ucenter/user/unlink-oauth'])?>", {"_csrf":_csrf, "id":id}, function(json) {
                json = $.parseJSON(json);
                if (json.ok) {
                    swal({title: '提示', text: '已解绑！', type: "success", confirmButtonText: "返回列表"}, function() {
                        window.location.reload();
                    });
                } else {
                    swal({title: '错误！', text: "操作失败！", type: "error", confirmButtonText: "关闭"});
                }
            })
        })
    });
</script>
<?php \common\widgets\JsBlock::end();?>