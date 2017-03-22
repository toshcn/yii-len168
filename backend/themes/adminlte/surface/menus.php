<?php
/* @var $this yii\web\View */

use yii\widgets\Menu;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\widgets\EachMenu;
use common\widgets\JsBlock;

$this->title = '菜单';
$this->params['contentClass'] = 'gray-bg';
?>
<div class="warpper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="m-b-md border-bottom white-bg dashboard-header">
                <form class="form-inline" action="<?= Url::to(['surface/menus'], true) ?>" method="get">
                    <?php if (count($navMenus) > 0) { ?>
                        <span class="" for="select-menu-to-edit">选择要编辑的菜单: </span>
                        <select class="form-control" name="menu" id="select-menu-to-edit">
                        <?php if ($navMenuId === 0) { ?>
                            <option value="">-请选择-</option>
                        <?php } ?>
                        <?php foreach ($navMenus as $key => $m) { ?>
                            <option value="<?= $m['termid'] ?>" <?php echo $navMenuId == $m['termid'] ? 'selected' : '' ?>><?= $m['title'] ?></option>
                        <?php } ?>
                        </select>
                        <button class="btn btn-default" type="submit">选择</button>
                    <?php } else { ?>
                        <span>在下方编辑您的菜单，</span>
                    <?php } ?>
                    <span class="add-new-menu-action">或<?= Html::a('创建新菜单', null, ['href' => Url::to(['surface/menus', 'menu' => 0], true)]) ?></span>
                </form>
            </div>
        </div>
        <div class="col-lg-4">
            <div id="accordion" class="accordion-container" style="<?= $navMenuId === 0 ? 'opacity:.6' : ''?>">
                <ul class="outer-border">
                    <li class="control-section accordion-section">
                        <form id="menu-item-post-form">
                            <h3 class="accordion-section-title">文章<i class="fa fa-chevron-down"></i></h3>
                            <div class="accordion-section-content">
                                <div class="tabs-container posttype-post">
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a data-toggle="tab" href="#post-recent" for="" aria-expanded="true">常用</a>
                                        </li>
                                        <li class="">
                                            <a data-toggle="tab" href="#post-search" aria-expanded="false">搜索</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div id="post-recent" class="tab-pane active">
                                            <div class="panel-body">
                                                <ul class="unstyled">
                                                    <?php foreach ($postRecent as $key => $post) { ?>
                                                    <li>
                                                        <label class="checkbox-label simple-txt">
                                                            <input class="" type="checkbox" name="post-recent-select" value="<?= $post['postid'] ?>"><?= $post['title'] ?>
                                                        </label>
                                                        <input type="hidden" name="menu-item-post[<?= $post['postid'] ?>][object]" value="<?= $post['postid'] ?>">
                                                        <input type="hidden" name="menu-item-post[<?= $post['postid'] ?>][type]" value="post">
                                                        <input type="hidden" name="menu-item-post[<?= $post['postid'] ?>][title]" value="<?= $post['title'] ?>">
                                                        <input type="hidden" name="menu-item-post[<?= $post['postid'] ?>][parent]" value="0">
                                                    </li>
                                                <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
                                        <div id="post-search" class="tab-pane">
                                            <div class="panel-body">
                                                <div class="menu-search-box">
                                                    <input class="form-control input-sm pull-left" type="text" maxlength="64" id="search-post-word" value="" placeholder="搜索文章标题" style="width: 60%;">
                                                    <button class="btn btn-primary btn-sm pull-right" id="js-search-post-btn" type="button">搜索</button>
                                                </div>
                                                <div class="search-result">
                                                    <ul class="unstyled" id="post-search-result">
                                                        <li>请搜索</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-t-md">
                                            <label class="input-label">
                                                <a href="javascript:;" name="select-all" id="post-select" for="post-recent" data-type="post">全选</a>
                                            </label>
                                            <span class="pull-right"><button class="btn btn-info btn-sm" id="submit-menu-item-post" type="button" data-url="<?= Url::to(['surface/ajax-create-menu'], true)?>" <?= $navMenuId === 0 ? 'disabled' : '' ?> for="post-recent-select">添加到菜单</button></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </li>
                    <li class="control-section accordion-section">
                        <form id="menu-item-link-form">
                            <h3 class="accordion-section-title">链接<i class="fa fa-chevron-down"></i></h3>
                            <div class="accordion-section-content" style="display:none">
                                <div class="tabs-container">
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a data-toggle="tab" href="#link-recent" aria-expanded="true" data-target="link-select">最近</a>
                                        </li>
                                        <li class="">
                                            <a data-toggle="tab" href="#link-search" aria-expanded="false" data-target="link-select">搜索</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div id="link-recent" class="tab-pane active">
                                            <div class="panel-body">
                                                <ul class="unstyled">
                                                <?php foreach ($linkRecent as $key => $link) { ?>
                                                    <li>
                                                        <label class="checkbox-label simple-txt">
                                                            <input class="" type="checkbox" name="link-recent-select" value="<?= $link['linkid'] ?>"><?= $link['link_title'] ?>
                                                        </label>
                                                        <input type="hidden" name="menu-item-link[<?= $link['linkid'] ?>][object]" value="<?= $link['linkid'] ?>">
                                                        <input type="hidden" name="menu-item-link[<?= $link['linkid'] ?>][type]" value="link">
                                                        <input type="hidden" name="menu-item-link[<?= $link['linkid'] ?>][title]" value="<?= $link['link_title'] ?>">
                                                        <input type="hidden" name="menu-item-link[<?= $link['linkid'] ?>][parent]" value="0">
                                                    </li>
                                                <?php } ?>
                                                </ul>

                                            </div>
                                        </div>
                                        <div id="link-search" class="tab-pane">
                                            <div class="panel-body">
                                                <div class="menu-search-box">
                                                    <input class="form-control input-sm pull-left" type="text" maxlength="64" id="search-link-word" value="" placeholder="搜索链接标题" style="width: 60%;">
                                                    <button class="btn btn-primary btn-sm pull-right" id="js-search-link-btn" type="button">搜索</button>
                                                </div>
                                                <div class="search-result">
                                                    <ul class="unstyled" id="link-search-result">
                                                        <li>请搜索</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-t-md">
                                            <label class="input-label"><a href="javascript:;" name="select-all" id="link-select" for="link-recent" data-type="link">全选</a></label>
                                            <span class="pull-right"><button class="btn btn-info btn-sm" id="submit-menu-item-link" type="button" data-url="<?= Url::to(['surface/ajax-create-menu'], true)?>" <?= $navMenuId === 0 ? 'disabled' : '' ?> for="link-recent-select">添加到菜单</button></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </li>
                    <li class="control-section accordion-section">
                        <form id="menu-item-term-form">
                        <h3 class="accordion-section-title">分类<i class="fa fa-chevron-down"></i></h3>
                        <div class="accordion-section-content" style="display:none">
                            <div class="tabs-container">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a data-toggle="tab" href="#term-recent" aria-expanded="true" data-target="term-select">最近</a>
                                    </li>
                                    <li class="">
                                        <a data-toggle="tab" href="#term-search" aria-expanded="false" data-target="term-select">搜索</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div id="term-recent" class="tab-pane active">
                                        <div class="panel-body">
                                            <ul class="unstyled">
                                                <?php foreach ($termRecent as $key => $term) { ?>
                                                    <li>
                                                        <label class="checkbox-label simple-txt">
                                                            <input class="" type="checkbox" name="term-recent-select" value="<?= $term['termid'] ?>"><?= $term['title'] ?>
                                                        </label>
                                                        <input type="hidden" name="menu-item-term[<?= $term['termid'] ?>][object]" value="<?= $term['termid'] ?>">
                                                        <input type="hidden" name="menu-item-term[<?= $term['termid'] ?>][type]" value="term">
                                                        <input type="hidden" name="menu-item-term[<?= $term['termid'] ?>][title]" value="<?= $term['title'] ?>">
                                                        <input type="hidden" name="menu-item-term[<?= $term['termid'] ?>][parent]" value="0">
                                                    </li>
                                                <?php } ?>
                                                </ul>
                                        </div>
                                    </div>
                                    <div id="term-search" class="tab-pane">
                                        <div class="panel-body">
                                            <div class="menu-search-box">
                                                <select class="form-control input-sm" id="search-term-word" style="width: 60%; float: left;">
                                                    <option value="0">一级分类</option>
                                                <?php foreach ($categorys as $key => $item) {?>
                                                    <option value="<?= $item['termid'] ?>"><?= $item['title'] ?></option>
                                                <?php } ?>
                                                </select>
                                                <button class="btn btn-primary btn-sm pull-right" id="js-search-term-btn" type="button">搜索</button>
                                            </div>
                                            <div class="search-result">
                                                <ul class="unstyled" id="term-search-result">
                                                    <li>请搜索</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-t-md">
                                        <label class="input-label">
                                            <a href="javascript:;" name="select-all" id="term-select" for="term-recent" data-type="term">全选</a>
                                        </label>
                                        <span class="pull-right">
                                            <button class="btn btn-info btn-sm" id="submit-menu-item-term" type="button" data-url="<?= Url::to(['surface/ajax-create-menu'], true)?>" <?= $navMenuId === 0 ? 'disabled' : '' ?> for="term-recent-select">添加到菜单</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-lg-8">

            <?php if ($navMenuId === 0) {
                $form = ActiveForm::begin([
                    'id' => 'navmenu-add-form',
                    'action' => Url::to(['surface/menus']),
                    'fieldConfig' => ['template' => ''],
                ]);
            ?>
                <div class="ibox surface-menu">
                    <div class="ibox-title surface-menu-name">
                        <label class="input-label pull-left">菜单名称</label>
                        <?= $form->field($menuForm, 'menuTitle', ['template' => '{input}'])->input('text', ['class' =>'form-control input-inline pull-left' , 'placeholder' => '在此输入菜单名称']) ?>
                        <div class="ibox-tools">
                            <button type="submit" class="btn btn-primary">创建菜单</button>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="m-b-lg">在上面给菜单命名，然后点击“创建菜单”。</div>
                    </div>

                    <div class="nav-menu-footer">
                        <div class="ibox-tools">
                            <button type="submit" class="btn btn-primary">创建菜单</button>
                        </div>
                    </div>
                </div>
            <?php ActiveForm::end(); ?>
            <?php } else {
                $form = ActiveForm::begin([
                    'id' => 'navmenu-edit-form',
                    'action' => Url::to(['surface/edit-nav-menu']),
                    'fieldConfig' => ['template' => ''],
                ]);
            ?>
                <div class="ibox surface-menu">
                    <div class="ibox-title surface-menu-name">
                        <label class="input-label pull-left">菜单名称</label>
                        <?= $form->field($termModel, 'title', ['template' => '{input}'])->input('text', ['class' =>'form-control input-inline pull-left', 'value' => $navMenu['title'], 'placeholder' => '在此输入菜单名称']) ?>
                        <input type="hidden" name="Term[termid]" value="<?= $navMenu['termid'] ?>">
                        <input type="hidden" name="navMenuId" value="<?= $navMenu['termid'] ?>">
                        <div class="ibox-tools">
                            <button type="button" class="btn btn-primary" name="submit-navmenu-edit-form">保存菜单</button>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <h3>菜单结构</h3>
                        <div class="m-b-lg drag-instructions"  style="display:<?= empty($navMenuItems) ? 'none' : 'block'; ?>">拖放各个项目到您喜欢的顺序，点击右侧的箭头可进行更详细的设置。</div>
                        <div id="menu-instructions" class="m-b-lg " style="display:<?= empty($navMenuItems) ? 'block' : 'none'; ?>"><p>从左边栏中添加菜单项目。</p></div>
                        <div class="dd" id="menu-sortable">
                        <?php echo EachMenu::widget([
                                'options' => ['class' => 'dd-list'],
                                'form' => $form,
                                'model' => $menuModel,
                                'menuItems' => $navMenuItems,
                                'navMenuId' => $navMenuId
                            ]);
                        ?>
                        </div>
                        <div class="p-t-md menu-settings">
                            <h3>菜单设置</h3>
                            <dl class="menu-theme-locations">
                                <dt class="howto">主题位置</dt>
                                <dd class="checkbox-input">
                                    <label class="checkbox-label">
                                        <input class="checkbox-inline" type="checkbox" value="1">
                                        <span class="theme-location-set"> （当前设置：顶部分类导航栏） </span>
                                    </label>
                                </dd>
                                <dd class="checkbox-input">
                                    <label class="checkbox-label">
                                        <input class="checkbox-inline" type="checkbox" name="menu-locations[pagemenu]" id="locations-pagemenu" value="38">
                                        <span for="locations-pagemenu">页面导航</span>
                                    </label>
                                </dd>
                            </dl>

                        </div>
                    </div>
                    <div class="nav-menu-footer">
                        <label class="input-label pull-left">
                            <a class="submitdelete deletion menu-delete" href="">删除菜单</a>
                        </label>
                        <div class="ibox-tools">
                            <button type="button" class="btn btn-primary" name="submit-navmenu-edit-form">保存菜单</button>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="deleteItem" value="">
            <?php ActiveForm::end(); ?>
            <?php } ?>
        </div><!-- .col-lg-8 -->
    </div>
</div>
<?php JsBlock::begin() ?>
    <script>
        var _postCatch = [];
        var _linkCatch = [];
        var _termCatch = [];
        $(document).ready(function(){
            var updateOutput = function (e) {
                var list = e.length ? e : $(e.target),
                        output = list.data('output');
                 if (window.JSON) {
                    output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
                } else {
                    output.val('JSON browser support required for this demo.');
                }
            };

            //更新菜单排序和父节点 递归方式
            var sortMenu = function(e, parent) {
                $.each(e, function(i, v) {
                    $('[name="Menus['+v.id+'][menu_parent]"]').val(parent);
                    $('[name="Menus['+v.id+'][menu_sort]"]').val(i);
                    if (v.children != undefined) {
                        sortMenu(v.children, v.id);
                    };
                });
            };

            // 菜单排序和父节点变化事件绑定
            $('#menu-sortable').nestable({
                group: 0
            }).on('change', function() {
                sortMenu($('#menu-sortable').nestable('serialize'), '0');
            });
            $('#menu-sortable').nestable('collapseAll');//折叠全部

            //菜单展开按钮鼠标点击事件绑定
            $('#menu-sortable').on('click.menu-item-dropdown-click', '.menu-item-dropdown', function() {
                var id = $(this).attr('for');
                $('#menu-handle-'+id).toggleClass('menu-item-open');
                $('#menu-item-'+id).slideToggle(300);
            });
            $('#menu-sortable').on('input.edit-menu-item-title', '.edit-menu-item-title', function() {
                if ($(this).val() == '') {
                    $('#'+$(this).attr('for')).html('<font class="no-title">(无标签)</font>');
                } else {
                    $('#'+$(this).attr('for')).text($(this).val());
                    $(this).parent().removeClass('has-error');
                }
            });
            //点击取消按钮触发click.menu-item-dropdown-click事件
            $('#menu-sortable').on('click.menu-item-cancel', '.menu-item-cancel', function(){
                $('#'+$(this).attr('for')).trigger('click.menu-item-dropdown-click');
            });
            //添加文章到菜单
            $('#submit-menu-item-post').on('click.menu-item-post-select', function() {
                var url = $(this).attr('data-url');
                var form = $(this).attr('for');
                var menus = new Array();
                $('[name="'+form+'"]:checked').each(function(i, e) {
                    var id = $(e).val();
                    menus[i] = '{"id":"'+id+'","object":"'+ $('[name="menu-item-post['+id+'][object]"]').val()+'",'+
                     '"type":"'+$('[name="menu-item-post['+id+'][type]"]').val()+'",'+
                     '"parent":"'+$('[name="menu-item-post['+id+'][parent]"]').val()+'",'+
                     '"title":"'+$('[name="menu-item-post['+id+'][title]"]').val()+'"}';
                });
                $.post(url, {'menus':'{"menus":['+menus.toString()+']}'}, function(json) {
                    var navMenuId = parseInt(<?= intval($navMenu['termid']) ?>);
                    $.each($.parseJSON(json), function(i, v) {
                        var itemId = v.menuid;
                        var id = v.cateid;
                        var title = $('[name="menu-item-post['+id+'][title]"]').val();
                        var parent = $('[name="menu-item-post['+id+'][parent]"]').val();
                        var html = createMenuLi(v, title, parent, '文章');
                        $('#menu-sortable > ol').append(html);
                    });
                });
            });
            //添加链接到菜单
            $('#submit-menu-item-link').on('click.menu-item-link-select', function() {
                var url = $(this).attr('data-url');
                var form = $(this).attr('for');
                var menus = new Array();
                $('[name="'+form+'"]:checked').each(function(i, e) {
                    var id = $(e).val();
                    menus[i] = '{"id":"'+id+'","object":"'+ $('[name="menu-item-link['+id+'][object]"]').val()+'",'+
                     '"type":"'+$('[name="menu-item-link['+id+'][type]"]').val()+'",'+
                     '"parent":"'+$('[name="menu-item-link['+id+'][parent]"]').val()+'",'+
                     '"title":"'+$('[name="menu-item-link['+id+'][title]"]').val()+'"}';
                });
                $.post(url, {'menus':'{"menus":['+menus.toString()+']}'}, function(json) {
                    var navMenuId = parseInt(<?= intval($navMenu['termid']) ?>);
                    $.each($.parseJSON(json), function(i, v) {
                        var itemId = v.menuid;
                        var id = v.cateid;
                        var title = $('[name="menu-item-link['+id+'][title]"]').val();
                        var parent = $('[name="menu-item-link['+id+'][parent]"]').val();
                        var html = createMenuLi(v, title, parent, '链接');
                        $('#menu-sortable > ol').append(html);
                    });
                });
            });
            //添加分类到菜单
            $('#submit-menu-item-term').on('click.menu-item-term-select', function() {
                var url = $(this).attr('data-url');
                var form = $(this).attr('for');
                var menus = new Array();
                $('[name="'+form+'"]:checked').each(function(i, e) {
                    var id = $(e).val();
                    menus[i] = '{"id":"'+id+'","object":"'+ $('[name="menu-item-term['+id+'][object]"]').val()+'",'+
                     '"type":"'+$('[name="menu-item-term['+id+'][type]"]').val()+'",'+
                     '"parent":"'+$('[name="menu-item-term['+id+'][parent]"]').val()+'",'+
                     '"title":"'+$('[name="menu-item-term['+id+'][title]"]').val()+'"}';
                });
                $.post(url, {'menus':'{"menus":['+menus.toString()+']}'}, function(json) {
                    var navMenuId = parseInt(<?= intval($navMenu['termid']) ?>);
                    $.each(json, function(i, v) {
                        var itemId = v.menuid;
                        var id = v.cateid;
                        var title = $('[name="menu-item-term['+id+'][title]"]').val();
                        var parent = $('[name="menu-item-term['+id+'][parent]"]').val();
                        var html = createMenuLi(v, title, parent, '分类');
                        $('#menu-sortable > ol').append(html);
                    });
                });
            });
            //删除菜单项
            $('#menu-sortable').on('click.delete-menu-item', '.submitdelete', function() {
                var objectid = $(this).attr('data-object');
                var termCateid = $(this).attr('data-term-cateid');
                if (objectid != undefined && termCateid != undefined) {
                    var del = $('[name="deleteItem"]').val()+','+objectid+'-'+termCateid;
                    $('[name="deleteItem"]').val(del);
                }
                $('#'+$(this).attr('for')).remove();
            });
            $('[data-toggle="tab"]').on('click', function() {
                var url = $(this).prop('href').split('#');
                var id = '#'+$(this).attr('data-target');
                var type = $(id).attr('data-type');
                $('#submit-menu-item-'+type).attr('for', url[1]+'-select');
                $(id).attr('for', url[1]);
                $('#'+url[1]).siblings().removeClass('active');
                $('#'+url[1]).addClass('active');
            });
            //全选
            $('[name="select-all"]').on('click', function() {
                var to = $(this).attr('for')+'-select';
                var type = $(this).attr('data-type');
                $('#submit-menu-item-'+type).attr('for', to);
                var len = $('[name="' + to + '"]').length;
                var i = 0;
                $('[name="' + to + '"]').each(function() {
                    if(this.checked) i++;
                    else this.checked = true;
                });
                if (i == len) {
                    $('[name="' + to + '"]').each(function() {
                        this.checked = false;
                    });
                };
            });
            //搜索文章
            $('#js-search-post-btn').on('click.search-post', function() {
                var title = $('#search-post-word').val();
                var html = '';
                if (!_postCatch[title]) {
                    $.post("<?= Url::to(['/post/ajax-search-posts']);?>", {"s":title}, function(json) {
                        if (json.length) {
                            $.each(json, function(i, v) {
                                html += '<li><label class="checkbox-label simple-txt"><input class="" type="checkbox" name="post-search-select" value="'+v.postid+'"></label>'+v.title+
                                        '<input type="hidden" name="menu-item-post['+v.postid+'][object]" value="'+v.postid+'">'+
                                        '<input type="hidden" name="menu-item-post['+v.postid+'][type]" value="post">'+
                                        '<input type="hidden" name="menu-item-post['+v.postid+'][title]" value="'+v.title+'">'+
                                        '<input type="hidden" name="menu-item-post['+v.postid+'][parent]" value="0">'+
                                    '</li>';
                            });
                        } else {
                            html = '<li>不存在此文章： "<span>'+title+'</span>"</li>';
                        }
                        $('#post-search-result').html(html);
                        _postCatch[title] = {"html":html};//缓存此关键词查找到的文章
                    });
                } else {
                    $('#post-search-result').html(_postCatch[title].html);
                }
            });
            //搜索链接
            $('#js-search-link-btn').on('click.search-link', function() {
                var title = $('#search-link-word').val();
                var html = '';
                if (!_linkCatch[title]) {
                    $.post("<?= Url::to(['/link/ajax-search-links']);?>", {"s":title}, function(json) {
                        if (json.length) {
                            $.each(json, function(i, v) {
                                html += '<li><label class="checkbox-label simple-txt"><input class="" type="checkbox" name="link-search-select" value="'+v.linkid+'"></label>'+v.link_title+
                                        '<input type="hidden" name="menu-item-link['+v.linkid+'][object]" value="'+v.linkid+'">'+
                                        '<input type="hidden" name="menu-item-link['+v.linkid+'][type]" value="link">'+
                                        '<input type="hidden" name="menu-item-link['+v.linkid+'][title]" value="'+v.link_title+'">'+
                                        '<input type="hidden" name="menu-item-link['+v.linkid+'][parent]" value="0">'+
                                    '</li>';
                            });
                        } else {
                            html = '<li>不存在此链接： "<span>'+title+'</span>"</li>';
                        }
                        $('#link-search-result').html(html);
                        _linkCatch[title] = {"html":html};//缓存此关键词查找到的链接
                    });
                } else {
                    $('#link-search-result').html(_linkCatch[title].html);
                }
            });
            //搜索分类
            $('#js-search-term-btn').on('click.search-term', function() {
                var id = $('#search-term-word').children('option:selected').val();
                var title = $('#search-term-word').children('option:selected').text();
                var html = '';
                if (!_termCatch[id]) {
                    $.post("<?= Url::to(['/surface/ajax-search-cates']);?>", {"id":id}, function(json) {
                        if (json.length) {
                            $.each(json, function(i, v) {
                                html += '<li><label class="checkbox-label simple-txt"><input class="" type="checkbox" name="term-search-select" value="'+v.termid+'"></label>'+v.title+
                                        '<input type="hidden" name="menu-item-term['+v.termid+'][object]" value="'+v.termid+'">'+
                                        '<input type="hidden" name="menu-item-term['+v.termid+'][type]" value="term">'+
                                        '<input type="hidden" name="menu-item-term['+v.termid+'][title]" value="'+v.title+'">'+
                                        '<input type="hidden" name="menu-item-term['+v.termid+'][parent]" value="0">'+
                                    '</li>';
                            });
                        } else {
                            html = '<li>不存在此分类： "<span>'+title+'</span>"</li>';
                        }
                        $('#term-search-result').html(html);
                        _termCatch[id] = {"html":html};//缓存此关键词查找到的分类
                    });
                } else {
                    $('#term-search-result').html(_termCatch[id].html);
                }
            });
            //提交navmenu-edit-form
            $('[name="submit-navmenu-edit-form"]').on('click.submit-navmenu-edit-form', function() {
                var flag = true;
                $('input.edit-menu-item-title').each(function() {
                    if ($(this).val() == '') {
                        $(this).parent().addClass('has-error');
                        flag = false;
                    } else {
                        $(this).parent().removeClass('has-error');
                    }
                });
                if(flag) {
                    $('#navmenu-edit-form').submit();
                }
            });
        });
        function createMenuLi(menu, title, parent, name) {
            var itemId = menu.menuid;
            var html = '<li id="menu-item-'+itemId+'" class="dd-item menu-item menu-item-inactive" data-id="'+itemId+'">'+
                    '<a class="menu-item-dropdown" id="edit-'+itemId+'" href="javascript:;" title="编辑" for="edit-'+itemId+'"></a>'+
                    '<div class="dd-handle menu-item-open" id="menu-handle-edit-'+itemId+'">'+
                        '<span class="menu-item-title" id="menu-item-title-'+itemId+'">'+title+'</span>'+
                        '<div class="menu-controls pull-right">'+
                            '<span class="menu-item-type">'+name+'</span>'+
                            '<i class="fa fa-caret-down"></i>'+
                        '</div>'+
                    '</div>'+
                    '<div class="menu-item-settings" id="menu-item-edit-'+itemId+'" style="display:none">'+
                        '<div class="row">'+
                            '<div class="col-sm-12">'+
                                '<div class="" for="">导航标签<br/>'+
                                    '<div class="form-group field-menus-'+itemId+'-menu_title required">'+
                                        '<input class="form-control edit-menu-item-title" type="text" name="Menus['+itemId+'][menu_title]" value="'+title+'" autocomplete="off" for="menu-item-title-'+itemId+'" placeholder="在此输入导航标签">'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="menu-item-actions submitbox">'+
                            '<p class="link-to-original">原始: <a href="javascript:;">'+title+'</a></p>'+
                            '<a class="menu-item-delete submitdelete" href="javascript:;" data-object="'+itemId+'" data-term-cateid="'+menu.cateid+'" for="menu-item-'+itemId+'">删除</a>'+
                            '<span class="meta-sep"> | </span>'+
                            '<a class="menu-item-cancel submitcancel" href="javascript:;" for="edit-'+itemId+'">取消</a>'+
                        '</div>'+
                        '<input type="hidden" id="menu-item-parent-'+itemId+'" name="Menus['+itemId+'][menu_parent]" value="'+parent+'">'+
                        '<input type="hidden" id="menu-item-sort-'+itemId+'" name="Menus['+itemId+'][menu_sort]" value="0">'+
                        '<input type="hidden" name="TermRelations[][objectid]" value="'+itemId+'">'+
                    '</div>'+
                '</li>';
            return html;
        }
    </script>
<?php JsBlock::end() ?>