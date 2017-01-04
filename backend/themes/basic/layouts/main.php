<?php
use yii\widgets\Menu;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use backend\assets\BackendAsset;

/* @var $this \yii\web\View */
/* @var $content string */

BackendAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <!-- 初始网页宽度为设置屏幕宽度，缩放级别为1.0，禁止用户缩放-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <!-- 设置360等双内核的浏览器渲染模式 -->
    <meta name="renderer" content="webkit">
    <!-- 设置IE支持的最高模式 -->
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <!-- 禁止移动浏览器转码 -->
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <!-- 网站标志 -->
    <!-- <link rel="icon" type="image/png" href="favicon.png"> -->
    <title><?= Html::encode($this->title)  ?></title>
    <!-- 网站描述 -->
    <meta name="description" content="Yun+是一个完全响应式的后台主题UI框架，基于Boostrap CSS框架，jQuery框架等各种开源前端框架开发的HTML5+CSS3前端主题UI框架，提供各种强大的常用UI组件，您可以基于此框架应用于您的网站开发，如网站后台，会员中心，论坛，CMS等等。">
    <!-- 网站SEO关键词 -->
    <meta name="keywords" content="Yun+UI,云+主题,云加后台主题UI,响应式的后台主题,Boostrap主题框架">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link href="/public/css/site.css" rel="stylesheet">
</head>
<body>
    <?php $this->beginBody() ?>
    <div id="wrapper">
        <!-- 侧边导航菜单 开始 -->
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
            <?php
            echo Menu::widget([
                'options' => ['class' => ['nav metismenu'], 'id' => 'side-menu'],
                'encodeLabels' => false,
                'activateParents' => true,
                'items' => [
                    ['label' => '<div class="dropdown profile-element">
                            <span><img class="img-circle" src="/img/profile_small.jpg" alt="image"></span>
                            <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                                <span class="clear">
                                    <span class="block m-t-xs">
                                        <strong class="font-bold">Yun+ UI</strong>
                                    </span>
                                </span>
                                <span class="text-muted text-xs block">超级管理员 <b class="caret"></b></span>
                            </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a href="profile.html">项目</a></li>
                                <li><a href="contacts.html">联系我们</a></li>
                                <li><a href="mailbox.html">信箱</li>
                                <li class="divider"></li>
                                <li><a href="login.html">退出</a></li>
                            </ul>
                        </div>
                        <div class="logo-element">YUN+</div>',
                        'options' => ['class' =>['nav-header']],
                    ],
                    ['label' => '<i class="fa fa-tachometer"></i><span class="nav-tabel">仪表盘</span>', 'url' => ['system/index']],
                    ['label' => '<i class="fa fa-th-large"></i><span class="nav-tabel">设置</span><span class="fa arrow"></span>', 'url' => ['system/config'],
                    'submenuTemplate' => "\n<ul class=\"nav nav-second-level collapse\">\n{items}\n</ul>\n",
                        'items' => [
                            ['label' => '常规', 'url' => ['system/general']],
                        ],
                    ],
                    ['label' => '<i class="fa fa-paint-brush"></i><span class="nav-tabel">外观</span><span class="fa arrow"></span>', 'url' => ['surface/index'],
                    'submenuTemplate' => "\n<ul class=\"nav nav-second-level collapse\">\n{items}\n</ul>\n",
                        'items' => [
                            ['label' => '主题', 'url' => ['surface/themes']],
                            ['label' => '菜单', 'url' => ['surface/menus']],
                        ],
                    ],
                    ['label' => '<i class="fa fa-paint-brush"></i><span class="nav-tabel">链接</span><span class="fa arrow"></span>', 'url' => ['link/index'],
                    'submenuTemplate' => "\n<ul class=\"nav nav-second-level collapse\">\n{items}\n</ul>\n",
                        'items' => [
                            ['label' => '所有链接', 'url' => ['link/index']],
                            ['label' => '链接分类', 'url' => ['link/category']],
                        ],
                    ],
                    ['label' => '<i class="fa fa-paint-brush"></i><span class="nav-tabel">文章</span><span class="fa arrow"></span>', 'url' => ['post/index'],
                    'submenuTemplate' => "\n<ul class=\"nav nav-second-level collapse\">\n{items}\n</ul>\n",
                        'items' => [
                            ['label' => '所有文章', 'url' => ['post/index']],
                            ['label' => '文章分类', 'url' => ['post/category']],
                            ['label' => '更新分类', 'url' => ['post/update-category']],
                            ['label' => '标签', 'url' => ['post/tag']],
                        ],
                    ],
                    ['label' => '<i class="fa fa-paint-brush"></i><span class="nav-tabel">会员</span><span class="fa arrow"></span>', 'url' => ['user/index'],
                    'submenuTemplate' => "\n<ul class=\"nav nav-second-level collapse\">\n{items}\n</ul>\n",
                        'items' => [
                            ['label' => '所有会员', 'url' => ['user/index']],
                        ],
                    ],
                ],
            ]);
            ?>
            </div>
        </nav><!-- 侧边导航菜单 结束 -->

        <!-- 右侧内容 开始 -->
        <div class="gray-bg dashbard-1" id="page-wrapper">
            <!-- 右侧顶部导航div 开始 -->
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary" href="#"><i class="fa fa-bars"></i>
                        </a>
                        <form class="navbar-form-custom" action="search_results.html" role="search">
                            <div class="form-group">
                                <input class="form-control" id="top-search" name="top-search" type="text" placeholder="请输入您要查找的内容……">
                            </div>
                        </form>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li><span class="m-r-sm text-muted welcome-message">欢迎来到 YUN+ 后台UI主题</span></li>
                        <li class="dropdown">
                            <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                                <i class="fa fa-envelope"></i>
                                <span class="label label-warning">16</span>
                            </a>
                            <ul class="dropdown-menu dropdown-messages">
                                <li>
                                    <div class="dropdown-messages-box">
                                        <a class="pull-left" href="profile.html">
                                            <img class="img-circle" src="img/a7.jpg" alt="image">
                                        </a>
                                        <div class="media-body">
                                            <small class="pull-right">46h前</small>
                                            <strong>国民老公</strong>又在微博放料啦，某某女演员……<br>
                                            <small class="text-muted">3 前 7:58 下午 - 10.06.2014</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <div class="dropdown-messages-box">
                                        <a href="profile.html" class="pull-left">
                                            <img alt="image" class="img-circle" src="img/a4.jpg">
                                        </a>
                                        <div class="media-body ">
                                            <small class="pull-right text-navy">5小时前</small>
                                            <strong>股票大跌</strong> 小马哥不再是首富了。 <strong>———来自财经网</strong>. <br>
                                            <small class="text-muted">昨天 1:21 下午 - 11.06.2014</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <div class="dropdown-messages-box">
                                        <a href="profile.html" class="pull-left">
                                            <img alt="image" class="img-circle" src="img/profile.jpg">
                                        </a>
                                        <div class="media-body ">
                                            <small class="pull-right">23小时前</small>
                                            <strong>风姐</strong> 爱上 <strong>小马哥啦！</strong>. <br>
                                            <small class="text-muted">2天前 2:30 上午 - 11.06.2014</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <div class="text-center link-block">
                                        <a href="mailbox.html">
                                            <i class="fa fa-envelope"></i> <strong>查看全部消息</strong>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle count-info" data-toggle="dropdown" href="index.html#">
                                <i class="fa fa-bell"></i>
                                <span class="label label-primary">8</span>
                            </a>
                            <ul class="dropdown-menu dropdown-alerts">
                                <li>
                                    <a href="mailbox.html">
                                        <div>
                                            <i class="fa fa-envelope fa-fw"></i> 你有8条未读消息
                                            <span class="pull-right text-muted small">4 分钟前</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="profile.html">
                                        <div>
                                            <i class="fa fa-qq fa-fw"></i> 3条新回复
                                            <span class="pull-right text-muted small">12 分钟前</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="grid_options.html">
                                        <div>
                                            <i class="fa fa-upload fa-fw"></i> 服务器重启
                                            <span class="pull-right text-muted small">4 分钟前</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <div class="text-center link-block">
                                        <a href="notifications.html">
                                            <strong>查看全部提醒</strong>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="<?= Url::to(['auth/logout'], true); ?>">
                                <i class="fa fa-sign-out"></i> 退出
                            </a>
                        </li>
                        <li>
                            <a class="right-sidebar-toggle">
                                <i class="fa fa-tasks"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div><!-- 右侧顶部导航div 结束 -->

            <?= $content ?>

            <div class="footer">
                <div class="pull-right">
                    100000个访问<strong>总250w</strong>个访问.
                </div>
                <div>
                    <strong>Copyright</strong> yuntheme.com &copy; 2015
                </div>
            </div>
        </div><!-- 右侧内容 结束 -->

        <!-- 右上角隐藏通知，设置页 -->
        <div id="right-sidebar">
            <div class="sidebar-container">
                <ul class="nav nav-tabs navs-3">
                    <li class="active">
                        <a data-toggle="tab" href="dashboard_2.html#tab-1"> 通知 </a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="dashboard_2.html#tab-2"> 任务 </a>
                    </li>
                    <li class="">
                        <a data-toggle="tab" href="dashboard_2.html#tab-3"><i class="fa fa-gear"></i></a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div id="tab-1" class="tab-pane active">
                        <div class="sidebar-title">
                            <h3> <i class="fa fa-comments-o"></i> 最新通知 </h3>
                            <small><i class="fa fa-tim"></i> 您当前有10条未读通知.</small>
                        </div>

                        <div>
                            <div class="sidebar-message">
                                <a href="dashboard_2.html#">
                                    <div class="pull-left text-center">
                                        <img alt="image" class="img-circle message-avatar" src="img/a1.jpg">
                                        <div class="m-t-xs">
                                            <i class="fa fa-star text-warning"></i>
                                            <i class="fa fa-star text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        Yun+项目还要很多页面需要测试。
                                        <br>
                                        <small class="text-muted">今天 4:21 下午</small>
                                    </div>
                                </a>
                            </div>
                            <div class="sidebar-message">
                                <a href="dashboard_2.html#">
                                    <div class="pull-left text-center">
                                        <img alt="image" class="img-circle message-avatar" src="img/a2.jpg">
                                    </div>
                                    <div class="media-body">
                                        今天来面试美工的MM很不错，小刘你负责通知她明天到公司人事部办入职手续。
                                        <br>
                                        <small class="text-muted">今天 2:45 下午</small>
                                    </div>
                                </a>
                            </div>
                            <div class="sidebar-message">
                                <a href="dashboard_2.html#">
                                    <div class="pull-left text-center">
                                        <img alt="image" class="img-circle message-avatar" src="img/a3.jpg">

                                        <div class="m-t-xs">
                                            <i class="fa fa-star text-warning"></i>
                                            <i class="fa fa-star text-warning"></i>
                                            <i class="fa fa-star text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        朋友，那不是懒，懒是可以克服的。你只是脑子比较弱(笨)，没办法长时间经 受高强度的思考，去搞逻辑太复杂的东西和处理太多的信息量。
                                        <br>
                                        <small class="text-muted">昨天 1:10 下午</small>
                                    </div>
                                </a>
                            </div>
                            <div class="sidebar-message">
                                <a href="dashboard_2.html#">
                                    <div class="pull-left text-center">
                                        <img alt="image" class="img-circle message-avatar" src="img/a4.jpg">
                                    </div>

                                    <div class="media-body">
                                        很想和你去吹吹风，吹掉我给你的不愉快，只留下我给你的开心。
                                        <br>
                                        <small class="text-muted">昨天 8:37 上午</small>
                                    </div>
                                </a>
                            </div>
                            <div class="sidebar-message">
                                <a href="dashboard_2.html#">
                                    <div class="pull-left text-center">
                                        <img alt="image" class="img-circle message-avatar" src="img/a8.jpg">
                                    </div>
                                    <div class="media-body">
                                        单身很惭愧，恋爱很陶醉，结婚则太贵，离婚是因为不想浪费，再婚是因为无路可退。爱情没有不对，恋爱还要干脆，不要因为一只玫瑰而放弃整个春天的约会。
                                        <br>
                                        <small class="text-muted">今天 4:21 下午</small>
                                    </div>
                                </a>
                            </div>
                            <div class="sidebar-message">
                                <a href="dashboard_2.html#">
                                    <div class="pull-left text-center">
                                        <img alt="image" class="img-circle message-avatar" src="img/a7.jpg">
                                    </div>
                                    <div class="media-body">
                                        世上有两种女孩最可爱，一种是漂亮；一种是聪慧，而你是聪明的漂亮女孩。
                                        <br>
                                        <small class="text-muted">昨天 2:45 下午</small>
                                    </div>
                                </a>
                            </div>
                            <div class="sidebar-message">
                                <a href="dashboard_2.html#">
                                    <div class="pull-left text-center">
                                        <img alt="image" class="img-circle message-avatar" src="img/a3.jpg">

                                        <div class="m-t-xs">
                                            <i class="fa fa-star text-warning"></i>
                                            <i class="fa fa-star text-warning"></i>
                                            <i class="fa fa-star text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        “神雕侠侣”恋情曝光 同房过夜被拍。
                                        <br>
                                        <small class="text-muted">昨天 1:10 下午</small>
                                    </div>
                                </a>
                            </div>
                        </div>

                    </div>

                    <div id="tab-2" class="tab-pane">

                        <div class="sidebar-title">
                            <h3> <i class="fa fa-cube"></i> 最新任务</h3>
                            <small><i class="fa fa-tim"></i> 您当前有16个任务。 10个已完成。</small>
                        </div>

                        <ul class="sidebar-list">
                            <li>
                                <a href="dashboard_2.html#">
                                    <div class="small pull-right m-t-xs">6小时前</div>
                                    <h4>后台会员模块</h4>
                                    按进度完成后台会员模块开发。

                                    <div class="small">完成进度: 22%</div>
                                    <div class="progress progress-mini">
                                        <div style="width: 22%;" class="progress-bar progress-bar-warning"></div>
                                    </div>
                                    <div class="small text-muted m-t-xs">任务截止: 4:00 下午 - 12.06.2015</div>
                                </a>
                            </li>
                            <li>
                                <a href="dashboard_2.html#">
                                    <div class="small pull-right m-t-xs">9个小时前</div>
                                    <h4>后台订单模块bug处理 </h4>
                                    bug描述：订单模块支付未完成的订单会令订单金额变为负数；
                                    优先级：重大bug。
                                    <div class="small">完成进度: 48%</div>
                                    <div class="progress progress-mini">
                                        <div style="width: 48%;" class="progress-bar"></div>
                                    </div>
                                    <div class="small text-muted m-t-xs">任务截止: 4:00 下午 - 12.06.2015</div>
                                </a>
                            </li>
                            <li>
                                <a href="dashboard_2.html#">
                                    <div class="small pull-right m-t-xs">9个小时前</div>
                                    <h4>开发国庆促销专题页面</h4>
                                    同美工协同完成开发国庆促销专题页面。

                                    <div class="small">完成进度: 14%</div>
                                    <div class="progress progress-mini">
                                        <div style="width: 14%;" class="progress-bar progress-bar-info"></div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="dashboard_2.html#">
                                    <span class="label label-primary pull-right">新</span>
                                    <h4>APP页面测试</h4>
                                    <!--<div class="small pull-right m-t-xs">9 hours ago</div>-->
                                    新开发的APP各页面功能测试，完成后整理报告给项目经理。
                                    <div class="small">完成进度: 22%</div>
                                    <div class="small text-muted m-t-xs">任务截止: 4:00 下午 - 12.06.2015</div>
                                </a>
                            </li>
                            <li>
                                <a href="dashboard_2.html#">
                                    <div class="small pull-right m-t-xs">9个小时前</div>
                                    <h4>新功能开发</h4>
                                    订单系统添加一个一键下单功能。

                                    <div class="small">完成进度: 22%</div>
                                    <div class="progress progress-mini">
                                        <div style="width: 22%;" class="progress-bar progress-bar-warning"></div>
                                    </div>
                                    <div class="small text-muted m-t-xs">任务截止: 4:00 下午 - 12.06.2015</div>
                                </a>
                            </li>
                        </ul>

                    </div>

                    <div id="tab-3" class="tab-pane">

                        <div class="sidebar-title">
                            <h3><i class="fa fa-gears"></i> 设置 </h3>
                        </div>

                        <div class="setings-item">
                    <span>
                        显示通知
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example">
                                    <label class="onoffswitch-label" for="example">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                    <span>
                        隐藏聊天窗口
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" checked class="onoffswitch-checkbox" id="example2">
                                    <label class="onoffswitch-label" for="example2">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                    <span>
                        清空历史记录
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example3">
                                    <label class="onoffswitch-label" for="example3">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                    <span>
                        显示聊天窗口
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example4">
                                    <label class="onoffswitch-label" for="example4">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                    <span>
                        显示在线用户
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" checked name="collapsemenu" class="onoffswitch-checkbox" id="example5">
                                    <label class="onoffswitch-label" for="example5">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                    <span>
                        全局搜索
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" checked name="collapsemenu" class="onoffswitch-checkbox" id="example6">
                                    <label class="onoffswitch-label" for="example6">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                    <span>
                        每日动态
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example7">
                                    <label class="onoffswitch-label" for="example7">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="sidebar-content">
                            <h4>设置</h4>
                            <div class="small">
                                您可以从这里进行一些常规设置，当然这只是一个演示页面。
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- 右上角隐藏通知，设置页 end -->
    </div>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
