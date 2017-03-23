<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Posts;
use common\widgets\JsBlock;
use frontend\assets\ArticleAsset;
use frontend\assets\JqueryBlueimpAsset;

ArticleAsset::register($this);
JqueryBlueimpAsset::register($this);

$post['title'] = Html::encode($post['title']);

$this->params['bodyClass'] = 'gray-bg';
$this->title = $post['title'];
$my = Yii::$app->user->getIdentity();
?>
<div class="wrapper article-wrapper w-center">
    <div class="row">
        <div class="col-md-9" id="article-content">
            <div class="article-ibox">
                <div class="article-head">
                    <div class="article-title">
                        <div class="title">
                            <label class="label label-info pull-left">帮助</label>
                            <?= $post['title'] ?>
                        </div>

                        <div class="article-power red pull-left">
                            编辑：<?= Html::encode($post['nickname']) ?>
                            <?php if ($post['posttype'] == Posts::POST_TYPE_REPRINT) {?>
                                <a href="<?= Html::encode($post['original_link']) ?>" rel=nofollow>原文链接</a>
                            <?php } ?>
                            <a href="<?= Url::to(['/article/detail', 'id' => $post['postid']])?>" rel=nofollow>本文链接</a>
                        </div>

                        <div class="article-wealth gray pull-right">
                            <span><i class="fa fa-clock"></i>更新: <time class="timeago" datetime="<?= $post['updated_at']?>" title="<?= $post['updated_at']?>"><?= $post['updated_at']?></time></span>
                            <span><i class="fa fa-clock-o"></i>创建: <time class="timeago" datetime="<?= $post['created_at']?>" title="<?= $post['created_at']?>"><?= $post['created_at']?></time></span>
                            <span>来自: <?= $post['os'] ?></span>
                        </div>
                    </div>
                    <hr>
                </div>

                <div class="article-content">
                    <!-- 文章内容 -->
                    <div class="word-content" name="content-md" id="word-content"><?= $post['islock'] ? '本文已被锁定，暂时无法显示。' : Html::encode($post['content']); ?></div>
                </div>

                <div class="article-footer">
                    <div class="author">
                        编辑：<?= Html::encode($post['nickname']) ?>
                    </div>
                    <div class="copyright" title="<?= $post['copyrightStr'] ?>">
                         © 著作权归本站所有 <?= $post['copyrightStr'] ?>
                        <?php if ($post['posttype'] == Posts::POST_TYPE_REPRINT) {?>
                            <a href="<?= Html::encode($post['original_link']) ?>" rel=nofollow>原文链接</a>
                        <?php } ?>
                        <a href="<?= Url::to(['/article/detail', 'id' => $post['postid']])?>" rel=nofollow>本文链接</a>
                    </div>
                    <?php if ($post['pay_qrcode']) {?>
                    <div class="article-donate text-center">
                        <div class="article-donate-header">如果本站对你有帮助，请随意打赏。分享技术，快乐共享！</div>
                        <div class="article-donate-content">
                            <a href="<?= Html::encode($post['pay_qrcode']) ?>" data-gallery>
                                <button type="button" class="btn btn-warning">支持作者</button>
                            </a>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <!-- 文章页右边作者资料展示框 -->
        <div class="col-md-3">
            <div class="article-wrapper-right">
                <div class="article-right-head">
                    <div class="author-info-box js-uinfo-box" id="article-right-head">
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
                </div><!-- 文章页右边作者资料展示框 -->
                <?php if ($post['pay_qrcode']) {?>
                <div class="article-donate text-center">
                    <div class="article-donate-header">如果本站对你有帮助</div>
                    <div class="article-donate-content">
                        <a href="<?= Html::encode($post['pay_qrcode']) ?>" data-gallery>
                            <button type="button" class="btn btn-warning">支持本站</button>
                        </a>
                    </div>
                </div>
                <?php } ?>
            </div>

            <div id="blueimp-gallery" class="blueimp-gallery">
                <div class="slides"></div>
            </div>
        </div>

    </div>
</div>
<?php JsBlock::begin() ?>
<script>
    var _postid = parseInt("<?= $postid ?>");
    jQuery(function($) {
        $(document).ready(function() {
            //增加文章浏览量
            $.get("<?= Url::to(['/ajax/increase-views'], true) ?>", {"id": _postid});
            //文章内容转html
            createMarkdownHTMLView();
            var uid = parseInt("<?= $post['user_id']?>");
            //获取文章会员信息
            $.get(_authorWidgetUrl, {"id": uid}, function(html) {
                if (html) {
                    _authorWidget[uid] = html;
                   $('#article-right-head').html(html);
                }
            });
        });


        //创建markdown
        function createMarkdownHTMLView() {
            $('#article-content [name="content-md"]').each(function(i, e) {
                var id = $(e).prop('id');
                if (! $(e).hasClass('editormd-html-preview')) {
                    markdownToHTMLView(id, e);
                }
            });
        }
    });

</script>
<?php JsBlock::end() ?>
