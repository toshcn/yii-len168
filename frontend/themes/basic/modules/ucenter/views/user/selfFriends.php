<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */


use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
$this->title = '我的关注';
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
            <div class="user-center center-friend">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>我的关注<b class="red">(<?= $pagination->totalCount ?>)</b></h5>
                    </div>
                    <div class="ibox-content center-friend-list">
                        <?php foreach ($friends as $key => $friend) {?>
                            <div class="friend-box">
                                <a href="<?= Url::to(['/ucenter/user/detail', 'uid' => $friend['friend']['uid']], true); ?>" title="Ta的主页">
                                <div class="author-widget">
                                    <img class="img-circle circle-border" src="<?= $friend['friend']['head'] ?>" width="80" alt="">
                                    <div class="author-info-box js-uinfo-box animated flipInY" data-author="<?= $friend['friend']['uid'] ?>">
                                        <div class="author-info-loading">
                                            <div class="sk-spinner sk-spinner-wave">
                                                <div class="sk-rect1"></div>
                                                <div class="sk-rect2"></div>
                                                <div class="sk-rect3"></div>
                                                <div class="sk-rect4"></div>
                                                <div class="sk-rect5"></div>
                                            </div> 加载中
                                        </div>
                                    </div>
                                </div>

                                <div class="user-name">
                                    <?php
                                    switch ($friend['friend']['sex']) {
                                        case -1:
                                            echo '<i class="fa fa-transgender green" title="保密"></i>';
                                            break;
                                        case 1:
                                            echo '<i class="fa fa-mars green" title="男"></i>';
                                            break;
                                        case 0:
                                            echo '<i class="fa fa-venus green" title="女"></i>';
                                            break;
                                    }?>
                                    <?= Html::encode($friend['friend']['nickname'])?>
                                </div>
                                </a>
                            </div>
                        <?php } ?>
                        <div class="row">
                            <div class="col-md-12 align-center">
                                <?php echo LinkPager::widget([
                                    'linkOptions' => ['name' => 'pagination'],
                                    'pagination' => $pagination,
                                ]);?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>