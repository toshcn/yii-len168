<?php
use yii\helpers\Url;
use yii\helpers\Html;
/* @var $this yii\web\View */
$this->title = '我的文章';
$this->params['bodyClass'] = 'gray-bg';
?>
<div class="wrapper user-wrapper w-center">
    <div class="row">
        <div class="col-md-3">
            <!-- myUserInfoView -->
            <?= $this->render('_userInfoView', ['userDetail' => $userDetail]); ?>

            <!-- _myCenterLeftMenuView -->
            <?= $this->render('_userCenterLeftMenuView', ['uid' => $userDetail['uid']]); ?>

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
                                    <span class="text">[隐藏]</span>
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
                                <dd>上次登录IP:<span>[隐藏]</span></dd>
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