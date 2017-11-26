<?php
/**
 * @link http://www.shuimugua.com/
 * @copyright Copyright (c) 2015 shuimugua.com
 * @license
 */
use yii\widgets\LinkPager;
use common\widgets\JsBlock;
 /* @var $this yii\web\View */
?>
 <table class="table table-hover">
    <col width="25%">
    <col width="30%">
    <col width="10%">
    <col width="15%">
    <col width="">
    <thead>
        <tr>
            <th>邀请Email</th>
            <th>邀请码</th>
            <th>状态</th>
            <th>邀请时间</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
    <?php $now = time(); $timeOut = [];
        if ($invites) {
            foreach ($invites as $key => $item) { ?>
                <tr>
                    <td><?= $item['email'] ?></td>
                    <td><?= $item['code'] ?></td>
                    <td>
                    <?php if ($item['isuse']) { ?>
                        <lable class="label label-sm label-warning">
                            已注册
                        </lable>
                    <?php } else { ?>
                        <lable class="label label-sm label-default">
                            未注册
                        </lable>
                    <?php } ?>
                    </td>
                    <td id="send-at-<?= $item['id'] ?>"><?= substr($item['send_at'], 0, 10) ?></td>
                    <td>
                    <?php
                        $timeOut[$item['id']] = Yii::$app->params['inviteReSendTimeOut'] - ($now - strtotime($item['send_at']));
                        if ($timeOut[$item['id']] < 0) {
                            $timeOut[$item['id']] = 0;
                        }
                        if (!$item['isuse']) { ?>
                        <button type="button" class="btn btn-xs btn-success" id="resend-<?= $item['id'] ?>" name="resendBtn" data-id="<?= $item['id'] ?>">
                             <?= $timeOut[$item['id']] ? '<span class="timeout">'.$timeOut[$item['id']].'</span>秒后可' : ''; ?>重发
                        </button>
                    <?php } ?>
                    </td>
                </tr>
        <?php }} else {?>
            <tr><td colspan="5">无记录</td></tr>
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

<script>
    var temp = <?php echo json_encode($timeOut); ?>;
    setInviteTime(temp);
</script>
