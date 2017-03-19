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
$this->title = 'Ta的文章';
$this->params['bodyClass'] = 'gray-bg';
?>
<div class="wrapper user-wrapper w-center">
    <div class="row">
        <div class="col-md-3">
            <!-- userInfoView -->
            <?= $this->render('_userInfoView', ['userDetail' => $userDetail]); ?>

            <!-- _userCenterLeftMenuView -->
            <?= $this->render('_userCenterLeftMenuView', ['uid' => $userDetail['uid']]); ?>

        </div>
        <div class="col-md-9">
            <div class="user-center">

                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Ta的文章</h5>
                        <div class="ibox-tools">
                            <a href="<?= Url::to(['/ucenter/post/create']); ?>">
                                <button class="btn btn-success btn-sm" type="button">我要写文章</button>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content center-post-list">
                        <div class="col-md-12">
                            <table class="table">
                                <col width="55%" />
                                <col width="20%" />
                                <col width="15%" />
                                <col width="10%" />
                                <thead>
                                    <tr>
                                        <th>标题</th>
                                        <th>回复/浏览</th>
                                        <th>日期</th>
                                        <th>查看</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($posts as $key => $item) { ?>
                                        <tr>
                                            <td>
                                                <a href="<?= Url::to(['/article/detail', 'id' => $item['postid']], true); ?>" target="_blank">
                                                <?= Html::encode($item['title']) ?></a>
                                            </td>
                                            <td><?= $item['comments'] ?>/<?= $item['views'] ?></td>
                                            <td><?= substr($item['created_at'], 0, 10) ?></td>
                                            <td>
                                                <a href="<?= Url::to(['/article/detail', 'id' => $item['postid']], true); ?>" target="_blank">
                                                    <button type="button" class="btn btn btn-xs btn-success">查看</button>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>

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