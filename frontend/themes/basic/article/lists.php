<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */


use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Posts;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
$this->title = '文章分类';
$this->params['bodyClass'] = 'gray-bg';
$imageHost = Yii::$app->params['image.host'];

?>
<div class="wrapper category-wrapper w-center">
    <div class="content-box-left">
        <div class="category-list">
            <ul class="list-inline cateslist">
                <li class="main">分类目录：</li>
                <?php foreach ($categorys as $key => $item) {?>
                    <li><a href="<?= Url::to(['/article/lists', 'id' => $item['termid']], true)?>" data-hover="<?= $item['title'] ?>"><?= $item['title'] ?></a></li>
                <?php } ?>
            </ul>
        </div>
        <div class="post-list">
            <?php foreach ($posts as $key => $post) {
                $post['title'] = Html::encode($post['title']);
                $post['author'] = Html::encode($post['author']);
            ?>
                <div class="post-item">
                    <div class="item-left">
                        <div class="post-thumb">
                            <div class="overflash">
                                <a href="<?= Url::to(['/article/detail', 'id' => $post['postid']]) ?>" title="<?= $post['title'] ?>" target="_blank" rel="bookmark">
                                    <img src="/public/img/view.png" alt="" width="100%" >
                                </a>
                            </div>
                            <img class="post-thumb-img" src="<?= $post['image'] ? $post['image'] . '_326x195'  . $post['image_suffix'] : Yii::$app->params['post.header.image']; ?>" width="280" alt="<?= $post['title'] ?>">
                        </div>
                    </div>
                    <div class="item-right">
                        <div class="post-content">
                            <div class="post-title">
                                <a href="<?= Url::to(['/article/detail', 'id' => $post['postid']]) ?>" title="<?= $post['title'] ?>">
                                    <span class="orange">[<?= Posts::transformPostType($post['posttype']); ?>]</span>
                                    <?= $post['title'] ?>
                                </a>
                            </div>
                            <div class="post-detail">
                                <?= Html::encode(mb_substr($post['description'], 0, 147)) ?>
                            </div>
                        </div>
                        <div class="post-footer">
                            <div class="post-info">
                                <div class="author author-text author-widget pull-left">
                                    <img class="author-head img-circle" src="<?= $post['head'] ?>" width="30" height="30" alt="<?= Html::encode($post['nickname']) ?>">
                                    <div class="author-name" title="<?= $post['author'] ?>"><?= $post['author'] ?></div>
                                    <div class="author-info-box js-uinfo-box animated flipInY" data-author="<?= $post['user_id'] ?>">
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
                                <div class="pull-right">
                                    <span class="icon-txt"><i class="fa fa-eye"></i> <?= $post['views'] ?></span>
                                    <span class="icon-txt"><i class="fa fa-commenting-o"></i> <?= $post['comments'] ?></span>
                                    <span class="icon-txt">
                                        <time class="timeago" datetime="<?= $post['created_at'] ?>" title="<?= $post['created_at'] ?>"></time>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="post-hr"><hr></div>
            <?php } ?>

            <!-- 列表分页 -->
            <div class="align-center">
                <?php echo LinkPager::widget([
                    'linkOptions' => ['name' => 'pagination'],
                    'pagination' => $pagination,
                ]);?>
            </div><!-- 列表分页 end -->
        </div>
    </div>
    <div class="content-box-right"></div>
</div>