<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\User;
/* @var $this yii\web\View */
$this->title = '我的文章';
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
                    <div class="ibox-content user-info">
                        <div class="item">
                            <h2>个人资料</h2>
                            <ul class="">
                                <li>
                                    <span class="name">ID:</span>
                                    <span class="text"><?= $userDetail['uid'] ?></span>
                                </li>
                                <li>
                                    <span class="name">Email:</span>
                                    <span class="text"><?= $userDetail['email'] ?></span>
                                </li>
                            </ul>
                        </div>
                        <div class="item lively">
                            <h2>活跃概况</h2>
                            <ul class="list-inline">
                                <li>
                                    <span class="name">文章</span>
                                    <span class="text"><?= $userDetail['posts'] ?></span>
                                </li>
                                <li>
                                    <span class="name">评论</span>
                                    <span class="text"><?= $userDetail['comments'] ?></span>
                                </li>
                                <li>
                                    <span class="name">关注</span>
                                    <span class="text"><?= $userDetail['friends'] ?></span>
                                </li>
                                <li>
                                    <span class="name">粉丝</span>
                                    <span class="text"><?= $userDetail['followers'] ?></span>
                                </li>
                                <li>
                                    <span class="name">HP</span>
                                    <span class="text"><?= $userDetail['hp'] ?></span>
                                </li>
                                <li>
                                    <span class="name">水晶</span>
                                    <span class="text"><?= $userDetail['crystal'] ?></span>
                                </li>
                                <li>
                                    <span class="name">金币</span>
                                    <span class="text"><?= $userDetail['golds'] ?></span>
                                </li>
                            </ul>
                            <p class="motto">个人签名: <span class="name"> <?= $userDetail['motto'] ?></span></p>
                            <dl>
                                <dd>账号注册时间:<span><?= $userDetail['created_at'] ?></span></dd>
                                <dd>最近登录时间:<span><?= $userDetail['userLogin']['login_at'] ?></span></dd>
                                <dd>当前登录IP:<span><?= long2ip($userDetail['userLogin']['ipv4']) ?></span></dd>
                                <dd>上次登录IP:<span><?= long2ip($userDetail['userLogin']['last_ipv4']) ?></span></dd>
                            </dl>
                        </div>

                        <div class="item selfmsg">
                            <h2>个人信息</h2>
                            <dl>
                                <dd>真实姓名:<span><?= $userDetail['userInfo']['realname'] ?></span></dd>
                                <dd>手机:<span><?= $userDetail['userInfo']['mobile'] ?></span></dd>
                                <dd>地址:<span></span></dd>
                                <dd>QQ:<span></span></dd>
                                <dd>邮箱:<span><?= $userDetail['email'] ?></span></dd>
                                <dd>性别:<span><?= User::getSexName($userDetail['sex']) ?></span></dd>
                                <dd>生日:<span><?= $userDetail['userInfo']['birthday'] ?></span></dd>
                                <dd>身份证号:<span><?= $userDetail['userInfo']['idcode'] ?></span></dd>
                                <dd>个人签名:<span><?= $userDetail['motto'] ?></span></dd>
                            </dl>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php \common\widgets\JsBlock::begin(); ?>
<script>

</script>
<?php \common\widgets\JsBlock::end();?>