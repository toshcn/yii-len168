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
$this->title = '我的信息';
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
                        <h5>
                            <a <?= $search == 'to' ? 'class="current"' : ''; ?> href="<?= Url::to(['/ucenter/user/messages', 's' => 'to']); ?>">
                                收到的信息
                                <?php if ($search == 'to') {?>
                                (<?= $pagination->totalCount ?>)
                                <?php } ?>
                            </a>
                            <a <?= $search == 'from' ? 'class="current"' : ''; ?> href="<?= Url::to(['/ucenter/user/messages', 's' => 'from']); ?>">
                                发出的信息
                                <?php if ($search == 'from') {?>
                                (<?= $pagination->totalCount ?>)
                                <?php } ?>
                            </a>
                        </h5>
                    </div>
                    <div class="ibox-content">
                        <div id="vertical-timeline" class="vertical-container dark-timeline">
                        <?php if ($search == 'to') { ?>

                            <?php foreach ($messages as $key => $item) { ?>
                            <div class="vertical-timeline-block">
                                <div class="vertical-timeline-icon author author-widget">
                                    <img class="author-head img-circle" src="<?= $item['sendfrom']['head'] ?>" height="34">
                                </div>
                                <div class="vertical-timeline-content">
                                    <div class="vertical-timeline-header">
                                        <div class="author author-text author-widget pull-left">
                                            <div class="author-name">
                                            来自：<?= Html::encode($item['sendfrom']['nickname']); ?></div>
                                            <time class="timeago" datetime="<?= $item['send_at'] ?>" title="<?= $item['send_at'] ?>"></time>
                                            <div class="author-info-box js-uinfo-box animated flipInY" data-author="<?= $item['sendfrom']['uid'] ?>">
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
                                    </div>
                                    <p><?= Html::encode($item['content']); ?></p>
                                    <span class="vertical-date"></span>
                                </div>
                            </div>
                            <?php } ?>

                        <?php } else if ($search == 'from') { ?>

                            <?php foreach ($messages as $key => $item) { ?>
                            <div class="vertical-timeline-block">
                                <div class="vertical-timeline-icon author author-widget">
                                    <img class="author-head img-circle" src="<?= $item['sendto']['head'] ?>" height="34">
                                </div>
                                <div class="vertical-timeline-content">
                                    <div class="vertical-timeline-header">
                                        <div class="author author-text author-widget pull-left">
                                            <div class="author-name">
                                            发给：<?= Html::encode($item['sendto']['nickname']); ?></div>
                                            <time class="timeago" datetime="<?= $item['send_at'] ?>" title="<?= $item['send_at'] ?>"></time>
                                            <div class="author-info-box js-uinfo-box animated flipInY" data-author="<?= $item['sendto']['uid'] ?>">
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
                                    </div>
                                    <p><?= Html::encode($item['content']); ?></p>
                                    <span class="vertical-date"></span>
                                </div>
                            </div>
                            <?php } ?>
                        <?php } ?>
                        </div>
                    </div>
                    <div class="ibox-footer">
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
</div>
<?php \common\widgets\JsBlock::begin(); ?>
<script>

</script>
<?php \common\widgets\JsBlock::end();?>