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
use frontend\assets\SiteIndexAsset;

/* @var $this yii\web\View */
$this->params['bodyClass'] = 'gray-bg';
$this->title = 'LEN168首页';

SiteIndexAsset::register($this);
?>

<div class="wrapper slide-wrapper hidden-sm hidden-xs">
    <div class="slide-post w-center">
        <div class="carousel slide-carousel">
            <div class="slide-left pull-left">
                <div class="carousel-inner post-img">
                <?php foreach ($topPosts as $key => $top) { ?>
                    <div class="item animated pulse <?= ($key == 0) ? 'active' : '';?>" data-img-index="<?= $key + 1; ?>">
                        <a href="<?= Url::to(['/article/detail', 'id' => $top['postid']], true); ?>" target="_blank">
                            <img class="img-responsive" src="<?= $top['image'] ? $top['image'] . '_500x300' . $top['image_suffix'] : Yii::$app->params['post.header.image']; ?>" width="500" height="300" alt="<?= Html::encode($top['title']) ?>">
                        </a>
                    </div>
                <?php } ?>
                </div>
            </div>
            <div class="slide-right pull-right">
                <div class="carousel-inner post-text">
                <?php foreach ($topPosts as $key => $top) {
                    $top['author'] = Html::encode($top['author']);
                ?>
                    <div class="item animated fadeInDown <?= ($key == 0) ? 'active' : '';?> " data-post-index="<?= $key + 1; ?>">
                        <div class="post-content">
                            <div class="post-title">
                                <a href="<?= Url::to(['/article/detail', 'id' => $top['postid']], true); ?>" target="_blank">
                                    <span class="flag">[<?= Posts::transformPostType($top['posttype']); ?>]</span>
                                    <?= Html::encode($top['title']) ?>
                                </a>
                            </div>
                            <div class="post-detail">
                                <?= Html::encode(mb_substr($top['description'], 0, 177)) ?>
                            </div>
                        </div>
                        <div class="row post-footer">
                            <div class="post-info">
                                <div class="author author-text author-widget pull-left">
                                    <img class="author-head img-circle" src="<?= $top['head'] ?>" width="30" height="30" alt="<?= Html::encode($top['nickname']) ?>">
                                    <div class="author-name" title="<?= $top['author'] ?>"><?= $top['author'] ?></div>
                                    <div class="author-info-box js-uinfo-box animated flipInY" data-author="<?= $top['user_id'] ?>">
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
                                    <span class="icon-txt"><i class="fa fa-eye"></i> <?= $top['views'] ?></span>
                                    <span class="icon-txt"><i class="fa fa-commenting-o"></i> <?= $top['comments']?></span>
                                    <span><i class="fa fa-clock-o"></i> <time class="timeago" datetime="<?= $top['created_at']?>"><?= $top['created_at']?></time></span>
                                    <span class="icon-txt">
                                        <a class="btn btn-success btn-sm" href="<?= Url::to(['/article/detail', 'id' => $top['postid']], true); ?>" target="_blank">
                                            <i class="fa fa-book"></i> 阅读
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                </div>
            </div>
            <!-- 轮播（Carousel）导航 -->
           <a class="slide-control left" href="#post-img" name="slide-prev">&lsaquo;</a>
           <a class="slide-control right" href="#post-img" name="slide-next">&rsaquo;</a>
        </div>
    </div>
</div>

<div class="content-wrapper w-center">
    <div class="row">
        <div class="col-md-12">
            <ul class="list-inline cateslist">
                <li class="main">分类目录：</li>
                <?php foreach ($categorys as $key => $item) {?>
                    <li><a href="<?= Url::to(['/article/lists', 'id' => $item['termid']], true)?>" data-hover="<?= $item['title'] ?>"><?= $item['title'] ?></a></li>
                <?php } ?>
            </ul>
        </div>
        <?php
        if ($posts) {
            foreach ($posts as $key => $post) {
                $post['title'] = Html::encode($post['title']);
            ?>
            <div class="col-sm-4">
                <div class="post-box post-unview">
                    <div class="post-thumb">
                        <div class="overflash">
                            <a href="<?= Url::to(['/article/detail', 'id' => $post['postid']]) ?>" title="<?= $post['title'] ?>" rel="bookmark">
                                <img src="/public/img/view.png" alt="" width="100%" >
                            </a>
                        </div>
                        <img src="<?= $post['image'] ? Url::to($post['image'] . '_326x195' . $post['image_suffix'], true) : Yii::$app->params['post.header.image']; ?>" alt="" height="100%">
                    </div>
                    <div class="post-body">
                        <div class="post-content">
                            <h3 class="post-title">
                                <a href="<?= Url::to(['/article/detail', 'id' => $post['postid']]) ?>" title="<?= $post['title'] ?>" rel="bookmark"><?= $post['title'] ?></a>
                            </h3>
                            <div class="post-detail">
                                <?= Html::encode(mb_substr($post['description'], 1, 77)) ?>
                            </div>
                        </div>
                        <div class="post-footer">
                            <div class="post-info">
                                <div class="author author-text author-widget pull-left">
                                    <img class="author-head img-circle" src="<?= $post['head'] ?>" width="30" height="30" alt="<?= Html::encode($post['nickname']) ?>">
                                    <time class="timeago" datetime="<?= $post['created_at'] ?>" title="<?= $post['created_at'] ?>"></time>
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

                                <div class="pull-right align-right">
                                    <span class="icon-txt"><i class="fa fa-eye"></i> <?= $post['views'] ?></span>
                                    <span class="icon-txt"><i class="fa fa-commenting-o"></i> <?= $post['comments'] ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php } ?>
        <div class="col-sm-12 align-center">
            <?php echo LinkPager::widget([
                'linkOptions' => ['name' => 'pagination'],
                'pagination' => $pagination,
            ]);?>
        </div>
    </div>
</div>
