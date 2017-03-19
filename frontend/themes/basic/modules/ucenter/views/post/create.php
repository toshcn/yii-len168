<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use common\widgets\JsBlock;
use common\models\ImageForm;
use common\models\Posts;
use frontend\assets\EditArticleAsset;

EditArticleAsset::register($this);
/* @var $this yii\web\View */
$this->title = '发表文章';
$this->params['bodyClass'] = 'gray-bg';
$post['title'] = Html::encode($post['title']);
if ($parentPost) {
    $parentPost['title'] = Html::encode($parentPost['title']);
}
?>
<div class="wrapper user-wrapper w-center">
    <div class="row">
        <div class="col-md-12">
            <div class="postwrap">
                <div class="postcon">
                <?php $form = ActiveForm::begin([
                    'id' => 'post-form',
                ]);?>
                    <div class="posthead"><h3><?= $this->title ?></h3><hr></div>
                    <div class="row edit-title">
                        <div class="col-md-12 no-p-l"><label class="col-md-12">文章类型和标题：</label></div>
                        <div class="col-md-12 no-p-l">
                            <div class="form-group col-md-2 no-p-r">
                                <select class="form-control" name="PostForm[posttype]" id="post-type">
                                    <option value="1"><?= Yii::t('common', 'Original'); ?></option>
                                    <option value="2"><?= Yii::t('common', 'Reprint'); ?></option>
                                    <option value="3"><?= Yii::t('common', 'Translate'); ?></option>
                                </select>
                            </div>
                            <div class="form-group col-md-5 no-p-r">
                                <input class="form-control post-title" name="PostForm[title]" id="post-title" type="text" maxlength="45" placeholder="<?= Yii::t('common/label', 'Post Title'); ?>" value="<?= $post['title']; ?>">
                                <span class="title-len">还可输入<b id="title-len"><?= 45 - mb_strlen($post['title']); ?></b>个字</span>
                            </div>
                            <div class="form-group col-md-5">
                                <input class="form-control <?= $post['posttype'] != 2 ? 'hide' : ''; ?>" type="form-control" name="PostForm[originalUrl]" id="original-url" maxlength="255" placeholder="转载文章原键接" value="<?= $post['original_url']; ?>">
                            </div>
                        </div>

                        <div class="col-md-12 no-p-l"><label class="col-md-12">文章作者和版权：</label></div>
                        <div class="col-md-12 no-p-l">
                            <div class="form-group col-md-2 no-p-r">
                                <input class="form-control" name="PostForm[author]" id="author" type="text" maxlength="10" placeholder="作者/笔名" value="<?= $post['author']; ?>">
                            </div>
                            <div class="form-group col-md-3">
                                <select class="form-control" name="PostForm[copyright]">
                                    <option value="0">转载注明出处</option>
                                    <option value="1">转载请联系作者</option>
                                    <option value="2">禁止转载</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 no-p-l"><label class="col-md-12">文章分类：</label></div>
                        <div class="col-md-12 no-p-l" id="post-cate" data-row="1">
                            <div class="form-group col-xs-12 col-md-2 no-p-r" id="cate-select-1">
                                <select class="form-control cate-select" name="PostForm[categorys][]" data-index="1">
                                    <?php foreach ($categorys as $key => $cate) : ?>
                                        <option value="<?= $cate['termid']; ?>"><?= $cate['title']; ?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-12 no-p-l"><label class="col-md-12">关联父文章：</label></div>
                        <div class="col-md-12 no-p-l">
                            <div class="form-group col-md-6 no-p-r" id="parent-post">
                                <select class="form-control" name="PostForm[parent]" id="select-parent-post" data-placeholder="查找文章..." style="display:none">
                                    <option value="0">请选择……</option>
                                <?php if ($parentPost) { ?>
                                    <option value="<?= $parentPost['postid'];?>" selected><?= $parentPost['title'];?></option>
                                <?php } ?>
                                </select>
                                <div class="chosen-container chosen-container-single" style="width:90%">
                                    <a class="chosen-single" tabindex="-1">
                                        <span id="chosen-post-title">
                                        <?= $parentPost ? $parentPost['title'] : '搜索文章';?>
                                        </span>
                                        <div class="fix-drop-chosen-box-icon"><b></b></div>
                                    </a>
                                    <div class="chosen-drop">
                                        <div class="chosen-search">
                                            <input id="search-post-text" data-old="" type="text" autocomplete="off" tabindex="-1" placeholder="搜索文章……">
                                        </div>
                                        <ul class="chosen-results">
                                            <li class="active-result" data-post-id="0">请选择……</li>
                                        <?php if ($parentPost) { ?>
                                            <li class="active-result" data-post-id="<?= $parentPost['postid'];?>" selected><?= $parentPost['title'];?></li>
                                        <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-12 no-p-l">
                            <label class="col-md-12">设置封面图片：</label>
                            <div class="form-group col-md-2">
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#post-images" id="set-main-image-btn">设置封面图</button>
                            </div>

                            <div class="form-group col-md-10">
                                <img class="" id="privew-main-image" src="<?= Html::encode($post['image']);?>" alt="" width="326" height="195">
                            </div>
                        </div>
                    </div>
                    <div><label for="">文章正文：</label></div>
                    <div class="word-edit" id="word-edit">
                        <textarea class="editormd-markdown-textarea" name="PostForm[content]" id="post-content" cols="30" rows="10" maxlength="65535"><?= $post['content'];?></textarea>
                    </div>
                    <div class="word-len">你已输入<b id="word-len"><?= $post['content_len']?></b>个字符，还可以输入<b id="word-can-len"><?= 65535 - $post['content_len'];?></b>个字符！</div>

                    <div class="post-tag">
                        <div><label for="">设置标签（<sapn class="post-tag-info" for="">最多五个标签，多个标签逗号(,)或空格分隔</span>）</label></div>
                        <div class="row">
                            <div class="form-group col-md-6" style="position: relative;">
                                <input class="form-control post-tag-input" id="edit-post-tag" name="" type="text">
                                <div class="selected-tag" id="selected-tag">
                                <?php
                                if ($postid) {
                                    $currentTags = [];
                                    foreach ($postTags as $key => $tag) {
                                        $currentTags[] = $tag['termid'];
                                    ?>
                                        <label class="label label-sm label-warning post-tag-seleted" data-tag="<?= $tag['termid'];?>" title="点击删除">
                                            <input type="hidden" name="PostForm[tags][]" value="<?= $tag['title'];?>"><?= $tag['title'];?>
                                        </label>
                                <?php } ?>
                                <?php } ?>
                                </div>
                                <label class="post-tag-label" for="">在此输入标签</label>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-control control-label" id="hot-tag-docker">
                                    <label class="f-l">热门标签：</label>
                                    <label class="post-tagcloud f-l" id="post-tagcloud">
                                        <?php foreach ($hotTags as $key => $tag) { ?>
                                            <a href="javascript:;" data-tag="<?= $tag['termid'];?>"><?= $tag['title'];?></a>
                                        <?php } ?>
                                    </label>
                                    <label class="more-tag" id="more-tag">更多</label>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="word-desc">
                        <div class="clearfix"><label for="">简短描述</label></div>
                        <textarea class="form-control edit-desc" name="PostForm[description]" id="edit-desc" cols="10" rows="5" maxlength="250" placeholder="文章简短描述250字内"><?= $post['description'];?></textarea>
                        <span class="desc-len">还可输入<b id="desc-len"><?= 250-strlen($post['description']);?></b>个字</span>
                    </div>

                    <div class="post-config">
                        <div><label for="">附加选项</label></div>
                        <div class="col-md-6">
                            <dl class="dl-horizontal">
                                <dt>开放阅读：</dt>
                                <dd>
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="PostForm[isopen]" value="0" <?= $post['isopen'] ? '' : 'checked' ?>>
                                        <sapn for="">只限作者本人</sapn>
                                    </label>
                                </dd>
                                <dt>收费阅读：</dt>
                                <dd>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <select class="form-control" id="paytype" name="PostForm[paytype]">
                                                <option value="0">收费类型</option>
                                                <option value="1">金币</option>
                                                <option value="2">水晶</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <input class="form-control" id="spend" name="PostForm[spend]" type="text" placeholder="数量">
                                        </div>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="dl-horizontal">
                                <dt>开放评论：</dt>
                                <dd>
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="PostForm[iscomment]" value="0" <?= $post['iscomment'] ? '' : 'checked' ?>>
                                        <sapn for="">关闭</sapn>
                                    </label>
                                </dd>
                                <?php if (!$postid) {?>
                                <dt>自动存稿：</dt>
                                <dd>
                                    <label class="checkbox-label">
                                        <input type="checkbox" id="auto-save" value="1" checked>
                                        <sapn>开启 (每十分钟保存一次草稿)</sapn>
                                    </label>
                                </dd>
                                <?php } ?>
                            </dl>
                        </div>
                    </div>

                    <div class="post-submit">
                        <?php if ($post['status'] == Posts::STATUS_ONLINE) {?>
                            <button id="subPostForm" type="button" class="btn btn-success">更新</button>
                        <?php } else {?>
                            <button id="subPostForm" type="button" class="btn btn-success">发表</button>
                            <button id="subDraftForm" type="button" class="btn btn-default">保存草稿</button>
                        <?php } ?>
                    </div>

                    <input type="hidden" name="PostForm[image]" maxlength="255" value="<?= $post['image'];?>">
                    <input type="hidden" name="PostForm[imageSuffix]" maxlength="20" value="<?= $post['image_suffix'];?>">
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 图片管理 -->
<?= $this->render('_imageUpload', ['imageModel' => $imageModel, 'postid' => $postid]) ?>

<?php JsBlock::begin() ?>
<script>
    var postid = parseInt('<?= $postid; ?>');//文章id
    var _cateCatch = new Array();//分类缓存
    var _postCatch = new Array();//文章缓存

    $(document).ready(function(){
        <?php if ($post['status'] == Posts::STATUS_DRAFT) {?>
            $('#navbar-header').parent().removeClass('fixed-nav');
            $('#navbar-header').removeClass('navbar-fixed-top');
        <?php } ?>
        <?php if ($postid) { ?>
            var currentTags = <?= json_encode($currentTags);?>;
            var currentCates = [<?= $post['categorys'];?>];
            $('[name="PostForm[posttype]"').val(<?= $post['posttype'];?>);
            $('[name="PostForm[copyright]"').val(<?= $post['copyright'];?>);
            $('#edit-post-tag').css('padding-left', $('#selected-tag').width()+8);
            $.each(currentTags, function(i, v) {
                $('#post-tagcloud>[data-tag="'+v+'"]').toggleClass('cur');
            });
            $.each(currentCates, function(i, v) {
                if (i == 0) $('[name="PostForm[categorys][]"][data-index="1"]').val(v);
                var row = $('#post-cate').attr('data-row');
                var cur = currentCates[i+1] ? currentCates[i+1] : 0;
                getCateList(v, i+2, row, cur);
            });

        <?php } ?>

        //查找文章
        $('#parent-post').data('post', {});//文章缓存
        $('#parent-post > .chosen-container #search-post-text').on('input', function() {
            var old = $(this).attr('data-old');
            var text = $.trim($(this).val());
            var html = '<li class="active-result" data-post-id="0">请选择……</li>';
            if (text && text != old) {
                if (_postCatch[text] == undefined) {
                    $.get("<?= Url::to(['/ucenter/post/ajax-search-posts']);?>", {"s":text, "pid":postid}, function(json) {
                        json = $.parseJSON(json);
                        if (json.length) {
                            $.each(json, function(i, v) {
                                html += '<li class="active-result" data-post-id="'+v.postid+'">'+v.title+'</li>';
                            });
                        } else {
                            html = '<li class="no-results">不存在此文章 "<span>'+text+'</span>"</li>';
                        }
                        $('#parent-post > .chosen-container').find('ul.chosen-results').first().html(html);
                        _postCatch[text] = {"html":html};//缓存此关键词查找到的文章
                    });
                } else {
                    $('#parent-post > .chosen-container').find('ul.chosen-results').first().html(_postCatch[text].html);
                }
                $(this).attr('data-old', text);
            }
        });
        //选择父文章
        $('#parent-post > .chosen-container').on('click', 'li.highlighted', function() {
            var id = $(this).attr('data-post-id');
            var title = $(this).text();
            $('#chosen-post-title').text(title);
            $('#select-parent-post').html('<option value="'+id+'" selected>'+title+'</option>');
        });
        //显示文章搜索下拉列表
        $('#parent-post > .chosen-container').on('click.chosen-container', '.chosen-single', function() {
            var contain = $(this).parent();

            $(contain).addClass('chosen-container-active chosen-with-drop');
            $(contain).find('#search-post-text').focus();

            $(contain).on('mouseover', '.active-result', function() {
                $(this).addClass('highlighted');
            });
            $(contain).on('mouseleave', '.active-result', function() {
                $(this).removeClass('highlighted');
            });
            //关闭搜索下拉列表
            $(contain).on('mouseleave', function() {
                $(contain).removeClass('chosen-container-active chosen-with-drop');
            });
        });
        //搜索图片

        $('#search-images-btn').on('click.search-images', function() {
            var cate = $('[name="search[cate]"]').val();
            var date = $('[name="search[update]"]').val();
            var obj = cate+date;
            var $div = $('#search-image-items');
            if ($div.data(obj) == undefined) {
                $.getJSON("<?= Url::to(['/ucenter/post/search-images'], true);?>", {"cate": cate, "date": date}, function(json) {
                    var html = '';
                    $div.data(obj + 'more', '');
                    $.each(json.images, function(i, v) {
                        html += createImageLi(v);
                    });
                    if (json.next > 1) {
                        $div.data(obj + 'more', '<div class="col-md-12 align-center m-t-20"><button class="btn btn-sm" id="loading-more-images" data-page="'+json.next+'" data-cate="'+json.cate+'" data-date="'+json.date+'">加载更多…</button></div>');
                    } else {
                        $div.data(obj + 'more', '');
                    }
                    $div.data(obj, html);
                    html += $div.data(obj + 'more');
                    if (html == '') {
                        html = '<div class="col-md-12 align-center m-t-20" id="noimages"><i class="fa fa-info"></i> 无记录</div>';
                    }
                    $('#search-image-items').html(html);
                });
            } else {
                $('#search-image-items').html($div.data(obj) + $div.data(obj + 'more'));
            }
        });
        //选择图片
        $('#search-image-items').on('click', 'li', function() {
            $('#search-image-items').find('li').removeClass('selected');
            $(this).addClass('selected');
            var data = $(this).data();
            var url = $(this).children('img').attr('src');
            $('#priview-img').attr('src', url);
            $('#priview-img').data(data);
            $('#priview-name').text(data.name+data.suffix);
            var date = data.time.split('-');
            $('#priview-time').text(date[0]+'年'+date[1]+'月'+date[2]+'日');
            $('#priview-px').text(data.width+' x '+data.height+'像素');
            $('#priview-url').val(url);
            $('#priview-title').val(data.title);
            $('#priview-edit').attr('data-id', data.id);
            $('#image-detail-box').show();
        });
        $('#set-main-image-btn').on('click', function() {
            $('#search-images-btn').trigger('click');
            $('#push-image-to-post').hide();
        });

        //加载更多图片---加载下一页
        $('#search-image-items').on('click', '#loading-more-images', function() {
            var cate = $(this).attr('data-cate');
            var date = $(this).attr('data-date');
            var p = $(this).attr('data-page');
            var obj = cate + date;
            var $div = $('#search-image-items');
            $.getJSON("<?= Url::to(['/ucenter/post/search-images'], true);?>", {"cate": cate, "date": date, "page": p}, function(json) {
                var html = $div.data(obj);
                $.each(json.images, function(i, v) {
                    html += '<li id="search-image-'+v.id+'" data-id="'+v.id+'" data-title="'+v.img_title+'" data-path="'+v.img_path+'" data-name="'+v.img_name+'" data-size="'+v.img_size+'" data-width="'+v.img_width+'" data-height="'+v.img_height+'" data-suffix="'+v.img_suffix+'" data-thumb_suffix="'+v.thumb_suffix+'" data-thumb_path="'+v.thumb_path+'" data-original="'+v.img_original+'" data-time="'+v.created_at+'">'+
                    '<img class="" src="'+_imageHost+v.thumb_path+v.img_name+'_326x195'+v.thumb_suffix+'" width="160" height="96" alt="">'+
                    '</li>';
                });
                if (json.next > 1) {
                    $div.data(obj + 'more', '<div class="col-md-12 align-center m-t-20"><button class="btn btn-sm" id="loading-more-images" data-page="'+json.next+'" data-cate="'+json.cate+'" data-date="'+json.date+'">加载更多…</button></div>');
                } else {
                    $div.data(obj + 'more', '');
                }
                $div.data(obj, html);
                $('#search-image-items').html(html + $div.data(obj + 'more'));
            });
        });

        //自动保存草稿
        if ($('#auto-save').prop('checked')) {
            autoSavePost();
        }

        $('#auto-save').on('click', function() {
            if ($('#auto-save').prop('checked')) {
                autoSavePost();
            }
        });
        //绑定分类事件
        $('[data-toggle="tooltip"]').tooltip();
        $('#post-cate').data('cate', {});//清空分类缓存
        $('#post-cate').on('change.post-cate','[name="PostForm[categorys][]"]', function() {
            var id = $(this).val();
            var index = parseInt($(this).attr('data-index'))+1;
            var row = $('#post-cate').attr('data-row');
            if (id == 0) {_cateCatch[id] = {"html": ''};}//如果分类id为0，设置缓存为空
            if (_cateCatch[id] == undefined) {
                //如所选分类没有缓存，ajax请求此分类的子分类
                getCateList(id, index, row, 0);
            } else {
                for (var i = row; i > index-1; i--) {
                    $('#cate-select-'+i).remove();
                }
                if (_cateCatch[id].html != '') $('#post-cate').attr('data-row', index);
                else $('#post-cate').attr('data-row', index-1);

                $('#post-cate').append(_cateCatch[id].html);
            }
        });
        //图像上传
        $('#upload-image-head').on('click', function() {
            $('#chosen-image-bar').hide();
            $('#upload-image-bar').show();
        });
        //上传文件
        $('#images-store-head').on('click', function() {
            $('#upload-image-bar').hide();
            $('#chosen-image-bar').show();
        });
        var $image = $("#imgedit-cropper");
        var $dataHeight = $('#dataHeight');
        var $dataWidth = $('#dataWidth');
        var $dataRotate = $('#dataRotate');
        $image.cropper({
            aspectRatio: 5 / 3,
            preview: ".imgedit-preview",
            viewMode: 1,
            minContainerWidth:750,
            minContainerHeight:450,
            minCropBoxWidth:500,
            minCropBoxHeight:300,
            crop: function(e) {
                $dataHeight.val(Math.round(e.height));
                $dataWidth.val(Math.round(e.width));
                $dataRotate.val(e.rotate);
            }
        });
        //编辑图像
        $('#priview-edit').on('click', function() {
            $('#post-images').modal('hide');
            var data = $('#priview-img').data();
            var url = data.path+data.name+data.original+data.suffix;
            $('#imgedit-cropper').prop('src', url);
            $('#imgedit-cropper').attr('data-id', data.id);
            $('#edit-image-title').val(data.title);
            $image.cropper('replace', url);
            $('#imgedit-modal').modal({backdrop: 'static', show:true});
        });
        //裁剪图像
        $('#save-crop').on('click', function() {
            var image = $image.cropper('getData');
            swal({
                title: "你确定要修改图像吗?",
                text: "如果此图有缩略图, 提交后缩略图也一并更新！",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确认修改",
                cancelButtonText: "取消",
                closeOnConfirm: false
            }, function () {
                var title = $('#edit-image-title').val();
                var id = $('#imgedit-cropper').attr('data-id');
                $.post("<?= Url::to(['/ucenter/post/edit-image'], true);?>", {"id": id, "title": title, "image": image}, function(json) {
                    json = $.parseJSON(json);
                    if (json.ok) {
                        swal({
                            title: "已修改",
                            text: "你已成功修改此图片！",
                            type: "success",
                            showCancelButton: false,
                            confirmButtonText: "确认",
                            cancelButtonText: "取消",
                        });
                        $('#images-store #search-image-'+id).attr('data-version', json.version);
                        $('#images-store #search-image-'+id).attr('data-title', title);
                        var data = $('#images-store #search-image-'+id).data();
                        var newImg = _imageHost+data.thumb_path+data.name+'_326x195'+data.thumb_suffix+'?v='+json.version;
                        $('#images-store  #search-image-'+id +'> img').prop('src', newImg);
                        $('#priview-img').prop('src', newImg);
                        $('#priview-url').prop('src', newImg);
                        $('#priview-title').val(title);
                    } else {
                        swal({
                            title: "修改失败",
                            text: json.error,
                            type: "error",
                            showCancelButton: false,
                            confirmButtonText: "确认",
                            cancelButtonText: "取消",
                        });
                    }
                });
            });
        });

        //发表文章
        $('#subPostForm').on('click.subPostForm', function() {
            savePost(0);
        });
        // 保存草稿
        $('#subDraftForm').on('click.subDraftForm', function() {
            savePost(1);
        });
        $('#original-url').change(function() {
            validateOriginalUrl();
        });
        // Methods
        $('[name="set-cropper"]').on('click', function () {
            var $this = $(this);
            var data = $this.data();
            var $target;
            var result;
            if ($image.data('cropper') && data.method) {
                data = $.extend({}, data); // Clone a new one
                if (typeof data.target !== 'undefined') {
                    $target = $(data.target);
                    if (typeof data.option === 'undefined') {
                        try { data.option = JSON.parse($target.val()); } catch (e) { console.log(e.message); }
                    }
                }
                result = $image.cropper(data.method, data.option, data.secondOption);
                switch (data.method) {
                    case 'scaleX':
                    case 'scaleY':
                        $(this).data('option', -data.option);
                        break;
                }
                if ($.isPlainObject(result) && $target) {
                    try {
                        $target.val(JSON.stringify(result));
                    } catch (e) {
                        console.log(e.message);
                    }
                }
            }
            return false;
        });
        // Keyboard
        $(document.body).on('keydown', function (e) {
            if (!$image.data('cropper') || this.scrollTop > 300) {
                return;
            }
            switch (e.which) {
                case 37:
                    e.preventDefault();
                    $image.cropper('move', -1, 0);
                    break;
                case 38:
                    e.preventDefault();
                    $image.cropper('move', 0, -1);
                    break;
                case 39:
                    e.preventDefault();
                    $image.cropper('move', 1, 0);
                    break;
                case 40:
                    e.preventDefault();
                    $image.cropper('move', 0, 1);
                    break;
            }
        });

    });

    function createImageLi(img) {
        return '<li id="search-image-'+img.id+'" data-id="'+img.id+'" data-title="'+img.img_title+'" data-path="'+img.img_path+'" data-name="'+img.img_name+'" data-size="'+img.img_size+'" data-width="'+img.img_width+'" data-height="'+img.img_height+'" data-suffix="'+img.img_suffix+'" data-version="'+img.img_version+'" data-thumb_suffix="'+img.thumb_suffix+'" data-thumb_path="'+img.thumb_path+'" data-original="'+img.img_original+'" data-time="'+img.created_at+'">'+
            '<img class="" src="'+_imageHost+img.thumb_path+img.img_name+'_326x195'+img.thumb_suffix+'?v='+img.img_version+'" width="160" height="96" data-src="'+_imageHost+img.thumb_path+img.img_name+'_326x195'+img.thumb_suffix+'" alt="">'+
        '</li>';
    }

    //ajax加载分类下拉列表
    function getCateList(id, index, row, cur) {
        $.get("<?= Url::to(['/ucenter/post/ajax-cate-childrens'], true);?>", {"id": id}, function(json){
            json = $.parseJSON(json);
            for (var i = row; i > index-1; i--) {
                $('#cate-select-'+i).remove();
            }
            var html = '';
            if (json.length > 0) {
                html = '<div class="form-group col-xs-12 col-md-2 no-p-r" id="cate-select-'+index+'"><select class="form-control cate-select" name="PostForm[categorys][]" data-index="'+index+'"><option value="0">请选择分类</option>';
                $.each(json, function(i, v) {
                    var selected = cur == v.termid ? 'selected' : '';
                    html += '<option value="'+v.termid+'"' + selected +'>'+v.title+'</option>';
                });
                html += '</select></div>';
                $('#post-cate').attr('data-row', index);
                $('#post-cate').append(html);
            } else {
                $('#post-cate').attr('data-row', index-1);
            }
            _cateCatch[id] = {"html": html};//缓存此分类
        });
    }

    //验证转载键接
    function validateOriginalUrl(show) {
        var http = /^(http|https):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(?::\d{1,5})?(?:$|[?\/#])/i;
        if (!http.test($('#original-url').val())) {
            if (show) {
                swal({title: '提示', text: '请填写正确的链接！', type: "error", confirmButtonText: "确定"});
                $('#original-url').parent().addClass('has-error');
            }
            return false;
        }
        $('#original-url').parent().removeClass('has-error');
        return true;
    }

    //表单验证
    function validatePostForm(show) {
        if (!$('#post-title').val()) {
            if (show) {
                swal({title: '提示', text: '请填写标题！', type: "error", confirmButtonText: "确定"});
                $('#post-title').parent().addClass('has-error');
            }
            return false;
        }
        if ($('#post-type').val() == 2 && !validateOriginalUrl(show)) {
            return false;
        }
        if (!$('#author').val()) {
            if (show) {
                swal({title: '提示', text: '请填写作者！', type: "error", confirmButtonText: "确定"});
                $('#author').parent().addClass('has-error');
            }
            return false;
        }
        if (!$('#post-content').val()) {
            if (show)
                swal({title: '提示', text: '请填写内容！', type: "error", confirmButtonText: "确定"});
            return false;
        }
        if ($('#paytype').val() != 0 && !$('#spend').val()) {
            if (show) {
                swal({title: '提示', text: '请填写'+$('#paytype>option:checked').text()+'数量！', type: "error", confirmButtonText: "确定"});
                $('#spend').parent().addClass('has-error');
            }
            return false;
        }
        if ($('#post-type').val() == 2 && !validateOriginalUrl(show)) {
            return false;
        }
        return true;
    }
    <?php if (!$postid) {?>
    //自动保存草稿
    function autoSavePost() {
        var autoSave = setInterval(function () {
            if ($('#auto-save').prop('checked')) {
                if (validatePostForm(true)) {
                    var data = $('#post-form').serialize() + '&PostForm[isdraft]=1';
                    if (postid) {
                        data += '&PostForm[postid]='+postid;
                        var url = "<?= Url::to(['/ucenter/post/update'], true); ?>";
                    } else {
                        var url = "<?= Url::to(['/ucenter/post/create'], true); ?>";
                    }
                    $.post(url, data, function(json) {
                        json = $.parseJSON(json);
                        if (json.ok) {
                            postid = json.id;
                            if (json.tags) {
                                updateTags(json.tags);
                            }
                            toastr.success("已保存草稿: "+json.t, "提示");
                        } else {
                            toastr.error("无法保存草稿!", "提示");
                        }
                        toastr.options = {
                            "closeButton": true,
                            "debug": false,
                            "progressBar": true,
                        }
                    });
                }
            } else {
                clearInterval(autoSave);
            }
        }, 1000*10*60);
    }
    <?php }?>
    //发表文章
    function savePost(drfat) {
        if (validatePostForm(true)) {
            var data = $('#post-form').serialize() + '&PostForm[isdraft]=' + drfat;
            if (postid) {
                data += '&PostForm[postid]='+postid;
                var url = "<?= Url::to(['/ucenter/post/update'], true); ?>";
            } else {
                var url = "<?= Url::to(['/ucenter/post/create'], true); ?>";
            }
            $.post(url, data, function(json) {
                json = $.parseJSON(json);
                if (json.ok) {
                    if (!postid && json.id) {
                        postid = json.id;
                    }
                    if (!drfat) {
                        $('#subDraftForm').off('click.subDraftForm');
                        $('#subDraftForm').hide();
                    }
                    swal({title: '提示', text: '已成功保存！', type: "success", confirmButtonText: "返回文章列表", showCancelButton: true, cancelButtonText: "关闭"}, function() {
                        window.location.href = "<?= Url::to(['/ucenter/user/posts'], true); ?>";
                    });
                } else {
                    swal({title: '错误！', text: json.error, type: "error", confirmButtonText: "关闭"});
                }
            });
        }
    }

    //更新标签
    function updateTags(tags) {
        var divTag = $('#selected-tag');
        divTag.empty();
        $('#post-tagcloud a').removeClass('cur');
        $.each(tags, function(i, v) {
            var html = '<label class="label label-sm label-warning post-tag-seleted" data-tag="'+v.termid+'" title="点击删除"><input type="hidden" name="PostForm[tags][]" value="'+v.title+'">'+v.title+'</label>';
            divTag.append(html);
            $('#post-tagcloud').find('[data-tag="'+v.termid+'"]').addClass('cur');
        });
        $('#edit-post-tag').css('padding-left', $('#selected-tag').width()+8);
    }
</script>
<?php JsBlock::end() ?>