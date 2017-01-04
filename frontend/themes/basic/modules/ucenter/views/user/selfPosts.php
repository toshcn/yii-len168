<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use common\models\Posts;

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
                    <div class="ibox-title">
                        <h5>我的文章</h5>
                        <div class="ibox-tools">
                            <a href="<?= Url::to(['/ucenter/post/create']); ?>">
                                <button class="btn btn-success btn-sm" type="button">写文章</button>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="col-md-12 center-post-list">
                            <table class="table">
                                <col width="45%" />
                                <col width="20%" />
                                <col width="10%" />
                                <col width="15%" />
                                <col width="10%" />
                                <thead>
                                    <tr>
                                        <th>标题</th>
                                        <th>回复/浏览</th>
                                        <th>状态</th>
                                        <th>日期</th>
                                        <th>操作</th>
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
                                            <td><?= Posts::transformPostStatus($item['status']) ?></td>
                                            <td><?= substr($item['created_at'], 0, 10) ?></td>
                                            <td>
                                                <a href="<?= Url::to(['/ucenter/post/update', 'id' => $item['postid']], true); ?>">
                                                    <button type="button" class="btn btn btn-xs btn-success">编辑</button>
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