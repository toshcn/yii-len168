    <?php
use yii\helpers\Url;
use yii\helpers\Html;
/* @var $this yii\web\View */
$this->title = '个人主页';
$this->params['bodyClass'] = 'gray-bg';
?>
<div class="wrapper user-wrapper w-center">
    <div class="row">
        <div class="col-md-3">
            <!-- userInfoView -->
            <?= $this->render('_userInfoView', ['userDetail' => $userDetail]); ?>
            <!-- _userCenterLeftMenuView -->
            <?= $this->render('_userCenterLeftMenuView', ['userDetail' => $userDetail]); ?>

        </div>
        <div class="col-md-9">
            <div class="user-center">

                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Ta的文章</h5>
                        <div class="ibox-tools">
                            <a href="<?= Url::to(['/ucenter/post/new-post']); ?>">
                                <button class="btn btn-success btn-sm" type="button">我要写文章</button>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="row m-b-sm btn-sm">
                            <div class="col-md-1">
                                <button class="btn btn-default btn-sm" id="loading-example-brn" type="button">
                                    <i class="fa fa-refresh"></i>刷新
                                </button>
                            </div>
                        </div>
                        <div class="col-md-12 center-post-list">
                            <table class="table">
                                <col width="60%" />
                                <col width="25%" />
                                <col width="15%" />
                                <thead>
                                    <tr>
                                        <th>标题</th>
                                        <th>回复/浏览</th>
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
                                            <td>
                                                <a href="<?= Url::to(['/article/detail', 'id' => $item['postid']], true); ?>" target="_blank">
                                                    <button type="button" class="btn btn btn-xs btn-success">查看</button>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
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