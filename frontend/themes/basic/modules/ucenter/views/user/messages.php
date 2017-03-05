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
$this->title = '我的私信';
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
                        <h5>我的私信</h5>
                    </div>
                    <div class="ibox-content">
                        <div id="vertical-timeline" class="vertical-container dark-timeline">
                            <?php foreach ($messages as $key => $item) { ?>
                            <div class="vertical-timeline-block">
                                <div class="vertical-timeline-icon navy-bg">
                                    <i class="fa fa-briefcase"></i>
                                </div>
                                <div class="vertical-timeline-content">
                                    <h2><?= Html::encode($item['sendfrom']['nickname']); ?></h2>
                                    <p><?= Html::encode($item['content']); ?></p>
                                    <span class="vertical-date"></span>
                                </div>
                            </div>
                            <?php } ?>
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