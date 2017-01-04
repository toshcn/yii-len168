<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\LinkPager;
/* @var $this yii\web\View */
$this->title = '我的评论';
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
                        <h5>我的评论</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="col-md-12 center-post-list">
                            <table class="table">
                                <col width="55%" />
                                <col width="20%" />
                                <col width="15%" />
                                <col width="10%" />
                                <thead>
                                    <tr>
                                        <th>评论文章</th>
                                        <th>点赞/吐槽</th>
                                        <th>日期</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($comments as $key => $item) { ?>
                                        <tr>
                                            <td>
                                                <a href="<?= Url::to(['/article/detail', 'id' => $item['post_id']], true); ?>" target="_blank">
                                                <?= Html::encode($item['post']['title']) ?></a>
                                            </td>
                                            <td><?= $item['apps'] ?>/<?= $item['opps'] ?></td>
                                            <td><?= substr($item['comment_at'], 0, 10) ?></td>
                                            <td>
                                                <a href="<?= Url::to(['/article/detail', 'id' => $item['post_id']], true); ?>" target="_blank"" target="_blank">
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
<?php \common\widgets\JsBlock::begin(); ?>
<script>

</script>
<?php \common\widgets\JsBlock::end();?>