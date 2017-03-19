<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\models\PostAttributes;
use common\models\Posts;
use common\models\Comments;
use common\widgets\JsBlock;
use frontend\assets\ArticleAsset;
use frontend\assets\JqueryBlueimpAsset;

ArticleAsset::register($this);
JqueryBlueimpAsset::register($this);

$post['title'] = Html::encode($post['title']);

$this->params['bodyClass'] = 'gray-bg';
$this->title = $post['title'];
$my = Yii::$app->user->getIdentity();
$canComment = !$post['islock'] && !$post['isdie'] && $post['iscomment'] ? 1 : 0;
?>
<div class="wrapper article-wrapper w-center">
    <div class="row">

        <div class="col-md-9" id="article-content">
            <div class="article-ibox">
                <div class="article-head">
                    <div class="article-title">
                        <div class="title">
                            <label class="label label-info pull-left"><?= $post['typeStr'] ?></label>
                            <?= $post['title'] ?>
                        </div>
                        <div class="article-hp clearfix">
                            <div class="progress progress-striped active" title="生命值">
                            <?php
                            if ($post['progress'] > 50) {
                                $progressType = 'progress-bar-success';
                            } elseif ($post['progress'] > 20) {
                                $progressType = 'progress-bar-warning';
                            } else {
                                $progressType = 'progress-bar-danger';
                            }
                            ?>
                                <div style="width: <?= $post['progress'] ?>%" aria-valuemax="<?= PostAttributes::DEFAULT_HP ?>" aria-valuemin="0" aria-valuenow="<?= $post['progress'] ?>" role="progressbar" class="progress-bar <?= $progressType ?>">
                                    <span class="sr-only">HP(<?= $post['hp'] ?>)</span>
                                </div>
                            </div>
                        </div>
                        <div class="article-wealth gray">
                            <span>浏览(<?= $post['views'] ?>)</span>
                            <span>水晶(<?= $post['crystal'] ?>)</span>
                            <span>金币(<?= $post['golds'] ?>)</span>
                        </div>
                        <div class="article-power red pull-left">
                            文章由：<?= Html::encode($post['nickname']) ?>
                            <?= $post['typeStr'] ?>，<?= $post['copyrightStr'] ?>
                            <?php if ($post['posttype'] == Posts::POST_TYPE_REPRINT) {?>
                                <a href="<?= Html::encode($post['original_link']) ?>" rel=nofollow>原文链接</a>
                            <?php } ?>
                            <a href="<?= Url::to(['/article/detail', 'id' => $post['postid']])?>" rel=nofollow>本文链接</a>
                        </div>
                        <div class="article-wealth gray pull-right">
                            <span class="green"><i class="fa fa-thumbs-o-up"></i>点赞(<?= $post['apps'] ?>)</span>
                            <span class="red"><i class="fa fa-thumbs-o-down"></i>吐槽(<?= $post['opps'] ?>)</span>
                            <span><i class="fa fa-clock-o"></i> <time class="timeago" datetime="<?= $post['created_at']?>" title="<?= $post['created_at']?>"><?= $post['created_at']?></time></span>
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
                        本文由：<?= Html::encode($post['nickname']) ?>
                        <?= $post['typeStr'] ?>
                    </div>
                    <div class="copyright" title="<?= $post['copyrightStr'] ?>">
                         © 著作权归作者所有 <?= $post['copyrightStr'] ?>
                        <?php if ($post['posttype'] == Posts::POST_TYPE_REPRINT) {?>
                            <a href="<?= Html::encode($post['original_link']) ?>" rel=nofollow>原文链接</a>
                        <?php } ?>
                        <a href="<?= Url::to(['/article/detail', 'id' => $post['postid']])?>" rel=nofollow>本文链接</a>
                    </div>
                    <?php if ($post['pay_qrcode']) {?>
                    <div class="article-donate text-center">
                        <div class="article-donate-header">如果本文章让您的技术有所提升，请随意打赏。分享技术，快乐共享！</div>
                        <div class="article-donate-content">
                            <a href="<?= Html::encode($post['pay_qrcode']) ?>" data-gallery>
                                <button type="button" class="btn btn-warning">支持作者</button>
                            </a>
                        </div>
                    </div>
                    <?php } ?>
                </div>

                <!-- 我的点评 视图-->

                <?= $this->render('_myComment', [
                    'myComment' => $myComment,
                    'canComment' => $canComment
                ]); ?>
            </div>

            <!-- 评论 -->
            <div class="article-comment">
                <div class="article-comment-title">
                    <span class="comment-nav" id="sort-comment">
                        <a href="javascript:;" class="current" data-sort="asc">评论</a> | <a href="javascript:;" data-sort="desc">倒序排列</a>
                    </span>
                </div>
                <div class="comment-list" id="comment-list"></div>
            </div>


        </div>
        <!-- 文章页右边作者资料展示框 begin -->
        <?php if ($this->beginCache('article-author-cache')) { ?>
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
                </div><!-- 文章页右边作者资料展示框 end -->
                <?php if ($post['pay_qrcode']) {?>
                <div class="article-donate text-center">
                    <div class="article-donate-header">如果本文对你有帮助</div>
                    <div class="article-donate-content">
                        <a href="<?= Html::encode($post['pay_qrcode']) ?>" data-gallery>
                            <button type="button" class="btn btn-warning">支持作者</button>
                        </a>
                    </div>
                </div>
                <?php } ?>
            </div>

            <div id="blueimp-gallery" class="blueimp-gallery">
                <div class="slides"></div>
            </div>
        </div>
        <?php } ?>

    </div>
    <div class="hide" id="reply-template">
    <?php if (! \Yii::$app->user->isGuest) { ?>
        <div class="reply {standClass}">
            <div class="comment-author author author-widget">
                <a href="<?= Url::to(['/ucenter/center/detail', 'uid' => $my->uid], true) ?>">
                    <img class="img-circle" src="<?= $my->head ?>" alt="image">
                </a>
                <div class="author-info-box js-uinfo-box animated flipInY" data-author="<?= $my->uid ?>">
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
            <div class="comment-body">
                <div class="comment-head">
                    <em class="reply-floor">{floor}#</em>

                    <span class="author author-text author-widget">
                        <a href="<?= urldecode(Url::to(['/ucenter/center/detail', 'uid' => '{replyTo}'], true)) ?>">@{replyNickname}
                        </a>
                        <div class="author-info-box js-uinfo-box animated flipInY" data-author="{replyTo}" >
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
                    </span>
                    <span class="nickname">
                        <a href="javascript:;" id="reply-author-<?= $my->uid ?>"><?= Html::encode($my->nickname) ?></a>
                    </span>
                    <?php if ($my->isauth) { ?>
                        <span class="auth gray" title="未认证"><i class="fa fa-shield"></i></span>
                    <?php } else { ?>
                        <span class="auth green" title="已认证"><i class="fa fa-shield"></i></span>
                    <?php } ?>
                    <time class="timeago gray" datetime="{replyAt}">{replyAt}</time>
                    <span class="os gray">来自: {os}</span>
                </div>
                <textarea style="display:none;" name="content-md" data-id="{where}-md-{commentid}-{replyid}">{content}</textarea>
                <div class="comment-word">
                    <div id="{where}-md-{commentid}-{replyid}"></div>
                </div>
                <div class="comment-footer">{standStr}</div>
            </div>
        </div>
        <?php } ?>
    </div>

    <div class="reply-area hide" id="reply-area">
        <div class="reply-editer">
        <?php $form = ActiveForm::begin([
            'id' => 'sendReplyForm',
        ]);?>
            <div class="form-group reply-editer-ibox">
                <textarea class="form-control" name="reply[content]" rows="3" placeholder="回复内容不少于五个字！支持Markdown语法" maxlength="10000"></textarea>
                <div class="word-len">
                    <span class="pull-left"> @<b id="reply-at"></b></span>
                    你已输入<b id="reply-length">0</b>个字，还可以输入<b id="reply-can-length"><?= Comments::TEXT_MAX_LENGHT ?></b>个字！
                </div>
            </div>
            <div class="form-group">
                <label class="radio-inline green"><input type="radio" name="reply[stand]" value="1" checked><i class="fa fa-thumbs-o-up"></i>点赞</label>
                <label class="radio-inline red"><input type="radio" name="reply[stand]" value="-1"><i class="fa fa-thumbs-o-down"></i>吐槽</label>
                <button class="btn btn-xs btn-default pull-right" type="button" id="sendReplyBtn">回复</button>
            </div>
            <input type="hidden" name="reply[comment]" value="">
            <input type="hidden" name="reply[iscomment]" value="">
            <input type="hidden" name="reply[to]" value="">
        <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<!-- 无法评论提示弹出层 -->
<div class="message-widget" id="cannot-comment-widget" style="display:none">
    <div class="message-widget-box">
        <div class="message-widget-title">提示</div>
        <div class="message-widget-close">
            <a class="close-icon" name="message-widget-close" data-close="#cannot-comment-widget" title="关闭">
                <i class="fa fa-times"></i>
            </a>
        </div>
        <div class="message-widget-content" node-type="inner">
            <div class="message-frame">
                <div class="form-group">
                    <label class="text-center">本文评论暂时被关闭。</label>
                </div>
            </div>
        </div>
        <div class="message-widget-footer">
            <div class="bottom-right">
                <button type="button" class="btn btn-sm btn-success" name="message-widget-close" data-close="#cannot-comment-widget">关闭</button>
            </div>
        </div>
    </div>

</div>
<?php JsBlock::begin() ?>
<script>
    var _canConment = "<?= $canComment ?>";
    var _postid = <?= $postid ?>;
    var urlAsc = "<?= Url::to(['/ajax/article-comment', 'id' => $postid, 'sort' => 'asc'], true)?>";
    var urlDesc = "<?= Url::to(['/ajax/article-comment', 'id' => $postid, 'sort' => 'desc'], true)?>";
    var _articleCommentCatch = new Array();//分页评论数据缓存
        _articleCommentCatch['asc'] = new Array();
        _articleCommentCatch['desc'] = new Array();
    var _articleCommentReplyCatch = new Array();//回复分页数据缓存
    var _myArticleCommentReplyCatch = new Array();//my回复分页数据缓存

    jQuery(function($) {
        $(document).ready(function() {
            //增加文章浏览量
            $.get("<?= Url::to(['/ajax/increase-views'], true) ?>", {"id": _postid});
            //文章内容转html
            createMarkdownHTMLView();
            var uid = <?= $post['user_id']?>;
            //获取文章会员信息
            $.get(_authorWidgetUrl, {"id": uid}, function(html) {
                if (html) {
                    _authorWidget[uid] = html;
                   $('#article-right-head').html(html);
                }
            });
            //获取文章评论
            getArticleComment("<?= Url::to(['/ajax/article-comment', 'id' => $postid, 'sort' => 'asc'], true)?>", 0, 'asc');
            //评论排序
            $('#sort-comment').on('click', 'a', function() {
                if ($(this).hasClass('current')) {return false;}

                var sort = $(this).attr('data-sort');
                $('#sort-comment a').removeClass('current');
                $(this).addClass('current');

                if (sort == 'asc') {
                    getArticleComment(urlAsc, 0, 'asc');
                } else if (sort == 'desc') {
                    getArticleComment(urlDesc, 0, 'desc');
                }
            });
            //分页读取数据
            $('#article-comment').on('click.pageination', '[name="pagination"]', function() {
                if ($(this).parent().hasClass('active')) return false;
                var page = parseInt($(this).attr('data-page'));
                var sort = $('#sort-comment a.current').attr('data-sort');

                if (! _articleCommentCatch[sort][page]) {
                    getArticleComment($(this).prop('href'), page, sort);
                } else {
                    $('#comment-list').html(_articleCommentCatch[sort][page]);
                    $('#article-content .timeago').timeago();//时间美化
                    createMarkdownHTMLView();
                }
                return false;
            });


            $('#send-comment-login').on('click.forLogin', showLoginWidget);
            //统计输入字数
            $('[name="send[comment]"]').on('input', function() {
                var txt = $(this).val();
                var len = txt.replace(/[^\x00-\xff]/g,"aaa").length;
                var maxlen = <?= Comments::TEXT_MAX_LENGHT ?>;
                if (len > maxlen) {
                    $(this).val(txt.substr(0, maxlen));
                    $('#comment-length').text(0);
                } else {
                    $('#comment-length').text(maxlen - len);
                }
            });
            //发表评论
            $('#send-comment').on('click.sendComment', sendComment);
            //发表回复
            $('#sendReplyBtn').on('click.sendReply', sendReply);
            //回复显示表单
            $('body').on('click.reply', ' [name="reply-btn"]', function() {
                if (isGuest()) {
                    showLoginWidget();
                    return false;
                }

                if (_canConment == "0") {
                    showMessageWidget('#cannot-comment-widget');
                    return false;
                } else {
                    var uid = $(this).attr('reply-to');
                    var cid = $(this).attr('reply-comment');
                    var iscomment = $(this).attr('data-iscomment');
                    var $parent = $(this).parent().parent();
                    var at = $(this).attr('reply-author');

                    if ($parent.next().hasClass('reply-area')) {
                        $('#reply-area').toggleClass('hide');
                    } else {
                        $parent.after($('#reply-area'));
                        $('#reply-area').removeClass('hide');
                    }
                    $('[name="reply[to]"]').val(uid);
                    $('[name="reply[comment]"]').val(cid);
                    $('[name="reply[iscomment]"]').val(iscomment);
                    $('#reply-at').text(at);
                    $('[name="reply[content]"]').focus();
                }
            });
            //回复字数统计
            $('[name="reply[content]"], [name="send[comment]"]').on('input', function() {
                var len = $(this).val().length;
                var can = 1000 - len;
                len > 0 ? $(this).parent().removeClass('has-error') : $(this).parent().addClass('has-error');
                $('#reply-can-length').text(can);
                $('#reply-length').text(len);
            });
            //ajax获取回复
            $('#my-comment, #comment-list').on('click.replyMore', '[name="js-reply-more"]', ajaxReplyMore);
            $('#my-comment, #comment-list').on('click.replyMoreShow', '[name="js-reply-more-show"]', ajaxShowReplyMore);
        });
        //发表回复
        function sendReply() {
            var content = $('[name="reply[content]"]').val();
            if (content.length >= 5) {
                $('#sendReplyBtn').off('click.sendReply');
                var data = $('#sendReplyForm').serialize()+'&pid='+_postid;
                $.post("<?= Url::to(['/ucenter/article/send-reply']); ?>", data, function(json) {
                    if (json.ok) {
                        swal({
                            title: "回复成功！",
                            type: "success",
                            showCancelButton: false,
                            confirmButtonText: "确认",
                            closeOnConfirm: false,
                        });
                        $('[name="reply[content]"]').val('');
                        $('#reply-area').addClass('hide');
                        createReplyDom(json.reply, content, $('#reply-at').text());
                        changeCommentReply(json.reply);
                        //清空当前缓存
                        if ($('#article-comment').find('[name="comment-'+json.reply.comment+'"]')) {
                            var sort = $('#sort-comment a.current').attr('data-sort');
                            var page = $('#article-comment').find('li.active>[name="pagination"]').attr('data-page');
                            console.log(sort)
                            console.log(page)
                            _articleCommentCatch[sort][page] = '';
                        }
                    } else {
                        swal({
                            title: "回复失败！",
                            type: "error",
                            text: json.msg ? json.msg : '',
                            showCancelButton: false,
                            confirmButtonText: "确认",
                            closeOnConfirm: false,
                        });
                    }
                    $('#sendReplyBtn').on('click.sendReply', sendReply);
                });
            } else {
                $('[name="reply[content]"]').focus();
            }
        }

        //发表评论
        function sendComment() {
            if ($('[name="send[comment]"]').val().length < 5) {
                $('[name="send[comment]"]').focus();
            } else {
                $('#send-comment').off('click.sendComment');
                var data = $('#send-comment-form').serialize()+'&pid='+_postid;
                $.post("<?= Url::to(['/ucenter/article/send-comment']); ?>", data , function(json) {
                    if (json.ok == 1) {
                        var hp = "<?= Yii::$app->params['userWealth']['comment_hp']?>";
                        var gold = "<?= Yii::$app->params['userWealth']['comment_gold']?>";
                        swal({
                            title: "评论成功！",
                            text: "HP+" +hp+ ", 金币+" +gold,
                            type: "success",
                            showCancelButton: false,
                            confirmButtonText: "确认",
                            closeOnConfirm: false,
                        }, function() {
                            window.location.reload();
                        });
                    } else {
                        swal({
                            title: "评论失败！",
                            text: json.msg ? json.msg : '',
                            type: "error",
                            showCancelButton: false,
                            confirmButtonText: "确认",
                            closeOnConfirm: false,
                        });
                        $('#send-comment').on('click.sendComment', sendComment);
                    }
                });
            }
        }
        //创建回复
        function createReplyDom(reply, content, replyAuthor) {
            var standClass, standStr = '';
            switch (reply.stand) {
                case "1":
                    standStr = '<small class="green"><i class="fa fa-thumbs-o-up"></i>点赞</small>';
                    standClass = 'left-reply apps-reply';
                    break;
                case "-1":
                    standStr = '<small class="red"><i class="fa fa-thumbs-o-down"></i>吐槽</small>';
                    standClass = 'right-reply opps-reply';
                    break;
            }
            var rows = parseInt($('#article-content [name="comment-'+reply.comment_id+'"]').attr('data-rows')) + 1;
            $('#article-content [name="comment-'+reply.comment_id+'"]').attr('data-rows', rows);

            var template = {
                "{standClass}": standClass,
                "{floor}": rows,
                "{replyTo}": reply.reply_to,
                "{replyAt}": reply.reply_at,
                "{os}": reply.os,
                "{replyNickname}": replyAuthor,
                "{content}": content,
                "{standStr}": standStr,
                "{commentid}": reply.comment_id,
                "{replyid}": reply.replyid,
                "{where}": 'comment'
            };
            var myTemplate =  {
                "{standClass}": standClass,
                "{floor}": rows,
                "{replyTo}": reply.reply_to,
                "{replyAt}": reply.reply_at,
                "{os}": reply.os,
                "{replyNickname}": replyAuthor,
                "{content}": content,
                "{standStr}": standStr,
                "{commentid}": reply.comment_id,
                "{replyid}": reply.replyid,
                "{where}": 'my'
            };
            var html = $('#reply-template').html();
            var myHtml = html;
            $.each(template, function(index, v) {
                html = html.replace(new RegExp(index, 'g'), v);
            });
            $.each(myTemplate, function(index, v) {
                myHtml = myHtml.replace(new RegExp(index, 'g'), v);
            });
            $('#article-content [name="comment-'+reply.comment_id+'"]').append(html);

            $('#article-content [name="my-'+reply.comment_id+'"]').append(myHtml);


            $('#article-content .timeago').timeago();//时间美化
            var cmd = "comment-md-"+reply.comment_id+"-"+reply.replyid;
            var mymd = "my-md-"+reply.comment_id+"-"+reply.replyid;
            markdownToHTMLView(cmd, '[data-id="'+cmd+'"]');//markdown转换
            markdownToHTMLView(mymd, '[data-id="'+mymd+'"]');//markdown转换
        }
        //更改评论生命值,更改评论人数
        function changeCommentReply(reply) {
            if (reply.iscomment) {
                if (reply.stand == 1) {
                    var apps = $('#article-content [name="comment-apps-'+reply.comment_id+'"]');
                    apps.text(parseInt(apps.text()) + 1);
                } else if (reply.stand == -1) {
                    var opps = $('#article-content [name="comment-opps-'+reply.comment_id+'"]');
                    opps.text(parseInt(opps.text()) + 1);
                }
                var comm = $('#article-content [name="comment-hp-'+reply.comment_id+'"]');
                var hpnow = parseInt(comm.attr('aria-valuenow')) + parseInt(reply.hp);
                var hpmax = parseInt(comm.attr('aria-valuemax'));
                var pro = 0;
                if (hpnow > hpmax) {
                    pro = 100;
                } else {
                    pro = hpnow / hpmax * 100;
                }
                comm.attr('aria-valuenow', hpnow);
                comm.css('width', pro+'%');
                $('#article-content [name="hp-num-'+reply.comment_id+'"]').text(hpnow);
            }
        }
        //获取文章的评论
        function getArticleComment(url, page, sort) {
            if (! _articleCommentCatch[sort][page]) {
                $.get(url, function(html) {
                    if (html) {
                        _articleCommentCatch[sort][page] = html;
                        $('#comment-list').html(html);
                        $('#article-content .timeago').timeago();//时间美化
                        createMarkdownHTMLView();
                    } else {
                        $('#comment-list').html('<div class="no-comment">暂无评论</div>');
                    }
                });
            } else {
                $('#comment-list').html(_articleCommentCatch[sort][page]);
                $('#article-content .timeago').timeago();//时间美化
                createMarkdownHTMLView();
            }
        }
        //创建markdown
        function createMarkdownHTMLView() {
            $('#article-content [name="content-md"]').each(function(i, e) {
                var id = $(e).prop('id');
                if (! $(e).hasClass('editormd-html-preview')) {
                    markdownToHTMLView(id, e);
                }
            });
        }
        //加载评论
        function ajaxReplyMore(event) {
            var $this = $(event.currentTarget);
            $('#my-comment, #comment-list').off('click.replyMore', '[name="js-reply-more"]');
            $this.html('<i class="fa fa-spinner fa-spin"></i> 加载中…');
            var page = parseInt($this.attr('data-page'));
            var commentId = parseInt($this.attr('data-comment'));
            var where = $this.attr('data-where');
            if (!_articleCommentReplyCatch[commentId]) {
                _articleCommentReplyCatch[commentId] = new Array();
            }
            if (!_myArticleCommentReplyCatch[commentId]) {
                _myArticleCommentReplyCatch[commentId] = new Array();
            }
            getAtricleCommentReply(commentId, page, where, $this);

            $('#my-comment, #comment-list').on('click.replyMore', '[name="js-reply-more"]', ajaxReplyMore);
        }
        //展开收起评论
        function ajaxShowReplyMore(event) {
            var $this = $(event.currentTarget);
            var commentId = parseInt($this.attr('data-comment'));
            var show = $this.attr('data-show');
            var $comment = $this.parent().parent();
            if (show == 'hide') {
                $comment.find('[data-node="reply"]').each(function(i, e) {
                    $(e).slideUp('slow');
                });
                $this.attr('data-show', 'show');
                $this.html('展开回复 <i class="fa fa-angle-double-down"></i>');
            } else {
                $comment.find('[data-node="reply"]').each(function(i, e) {
                    $(e).slideDown('slow');
                });
                $this.attr('data-show', 'hide');
                $this.html('收起回复 <i class="fa fa-angle-double-up"></i>');
            }

        }

        //获取评论的回复
        function getAtricleCommentReply(commentId, page, where, $that) {
            var rows = parseInt($that.parent().parent().attr('data-rows'));
            var size = <?= intval(Yii::$app->params['replyPageSize']) ?>;
            var ca = false;
            if (where == 'comment') {
                ca = _articleCommentReplyCatch[commentId][page];
            } else if (where == 'my') {
                ca = _myArticleCommentReplyCatch[commentId][page];
            }
            if (!ca) {
                $.get("<?= Url::to(['/ajax/article-comment-reply'], true) ?>", {"commid": commentId, "page": page, "where": where}, function(json) {
                    json = $.parseJSON(json);
                    if (json.html) {
                        if (where == 'comment') {
                            _articleCommentReplyCatch[commentId][page] = json.html;
                        } else if (where == 'my') {
                            _myArticleCommentReplyCatch[commentId][page] = json.html;
                        }

                        $('#article-content').find('[name="'+where+'-'+commentId+'"]').append(json.html);
                        $that.attr('data-page', page+1);
                        if (json.rows <= page * size + size) {
                            $that.parent().append('<a name="js-reply-more-show" data-comment="'+commentId+'" data-show="hide" data-size="'+json.size+'">收起回复 <i class="fa fa-angle-double-up"></i></a>');
                            $that.remove();
                        } else {
                            $that.html('加载回复');
                        }

                        $('#article-content .timeago').timeago();//时间美化
                        createMarkdownHTMLView();
                    }
                });
            } else {
                if (where == 'comment') {
                    $that.parent().before(_articleCommentReplyCatch[commentId][page]);
                } else if (where == 'my') {
                    $that.parent().before(_myArticleCommentReplyCatch[commentId][page]);
                }

                $that.attr('data-page', page+1);
                if (rows > (page+1) * size) {
                    $that.html('加载回复');
                } else {
                    $that.parent().append('<a name="js-reply-more-show" data-comment="'+commentId+'" data-show="hide" data-size="'+size+'">收起回复 <i class="fa fa-angle-double-up"></i></a>');
                    $that.remove();
                }

                $('#article-content .timeago').timeago();//时间美化
                createMarkdownHTMLView();
            }

        }

    });

</script>
<?php JsBlock::end() ?>