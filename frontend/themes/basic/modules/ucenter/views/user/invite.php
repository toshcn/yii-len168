<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use common\widgets\JsBlock;

/* @var $this yii\web\View */

$this->title = '邀请码-会员中心';
$this->params['bodyClass'] = 'gray-bg';

?>
<div class="wrapper user-wrapper w-center">
    <div class="row">
        <div class="col-md-3">
            <!-- myUserInfoView -->
            <?= $this->render('_myUserInfoView', ['userDetail' => $userDetail]); ?>

            <!-- _userCenterLeftMenuView -->
            <?= $this->render('_myCenterLeftMenuView', ['userDetail' => $userDetail]); ?>

        </div>
        <div class="col-md-9">
            <div class="user-center">

                <div class="ibox">
                    <div class="ibox-title">
                        <h5>邀请码</h5>
                    </div>
                    <div class="ibox-content center-post-list">
                        <div class="row m-b-sm btn-sm">
                            <div class="col-md-2">
                                <button class="btn btn-default btn-sm" id="reload-invite-list" type="button">
                                    <i class="fa fa-refresh"></i>刷新
                                </button>
                            </div>
                            <div class="col-md-6">
                                <?php $inviteForm = ActiveForm::begin([
                                    'id' => 'form-invite',
                                    'action' => Url::to(['/ucenter/user/invite-user'], 'https')
                                ]); ?>
                                <div class="input-group">
                                    <input type="text" class="form-control input-sm" name="InviteForm[email]" value="" placeholder="输入对方的邮箱，每天可邀请十位">
                                    <span class="input-group-btn">
                                        <button class="btn btn-success btn-sm" name="submit-invite-form" id="submit-invite-form" data-loading="发送中" type="button">邀请朋友</button>
                                    </span>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                            <div class="col-md-4" id="filter-invite-email" style="display:none;">
                                <label class="filter-error filter-sm">请输入正确的邮箱</label>
                            </div>
                        </div>
                        <div class="col-md-12 center-invite-list" id="center-invite-list">

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php JsBlock::begin(); ?>
<script>
    var _pageCatch = new Array();//分页数据缓存
    var _timeOut = new Array();//重发时间倒计时秒数，下标为id
    var _inviteReSendTimeOut = <?= intval(Yii::$app->params['inviteReSendTimeOut']); ?>;
    jQuery(function($) {
        $(document).ready(function() {
            getInvitesList("<?= Url::to(['/ucenter/user/get-invites-list'])?>", 0);

            $('#submit-invite-form').on('click.submitInviteForm', inviteGuest);

            //分页读取数据
            $('#center-invite-list').on('click.pageination', '[name="pagination"]', function() {
                if ($(this).parent().hasClass('active')) return false;
                var page = parseInt($(this).attr('data-page'));
                if (!_pageCatch[page]) {
                    getInvitesList($(this).prop('href'), page);
                } else {
                    $('#center-invite-list').html(_pageCatch[page]);
                }
                return false;
            });

            //刷新
            $('#reload-invite-list').on('click', function() {
                var act = $('#center-invite-list .pagination > li.active');
                var page = act.attr('data-page');
                if(page) {
                    getInvitesList(act.prop('href'), page);
                } else {
                    getInvitesList("<?= Url::to(['/ucenter/user/get-invite-list'])?>", 0);
                }
            })
        });
    });

    function getInvitesList(url, page) {
        if (!_pageCatch[page]) {
            $.get(url, function(html) {
                if (html) {
                    $('#center-invite-list').html(html);
                    _pageCatch[page] = html;
                }
            });
        }
    }

    //发送邀请
    function inviteGuest() {
        var that = $('#submit-invite-form');
        var $parent = $('[name="InviteForm[email]"]').parent().parent();
        if (!/^[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/.test($('[name="InviteForm[email]"]').val())) {
            $('#filter-invite-email>label').text('请输入正确的邮箱');
            $('#filter-invite-email').show();
            $parent.addClass('has-error');
            $('[name="InviteForm[email]"]').focus();
            return false;
        } else {
            $('#filter-invite-email').hide();
            $parent.removeClass('has-error');
            $(that).off('click.submitInviteForm');
        }
        var btnTxt = $(that).text();
        var loading = $(that).attr('data-loading');
        $(that).html('<i class="fa fa-spinner fa-spin"></i>' + loading);

        $.post('<?= Url::to(['/ucenter/user/invite-guest'], true);?>', $('#form-invite').serialize(), function(json) {
            json = $.parseJSON(json);
            if (json.error) {
                $('#filter-invite-email>label').text(json.msg);
                $parent.addClass('has-error');
                $('#filter-invite-email').show();
            } else {
                swal({title: '邀请成功', text: '邀请码已发送到对方邮箱！', type: "success", confirmButtonText: "确定"}, function() {
                    $('[name="InviteForm[email]"]').val('');
                });
                _timeOut[json.id] = new Array();
                _timeOut[json.id]['time'] = _inviteReSendTimeOut;
                appendInviteRow(json);
                cleanPageCatch();
            }
            $(that).html(btnTxt);
            $(that).on('click.submitInviteForm', inviteGuest);
        });
    }
    //重发邀请
    function resendInviteCode(event) {
        var id = event.target.dataset.id;
        var resendid = '#center-invite-list #resend-'+id;
        $(resendid).off('click.resendInviteCode');
        cleanPageCatch();
        var loading = '正在发送…';
        var timeoutid = resendid+'>.timeout';
        var time = _inviteReSendTimeOut;
        $(resendid).html('<i class="fa fa-spinner fa-spin"></i>' + loading);
        $.post('<?= Url::to(['/ucenter/user/resend-invite']);?>', {"id": id}, function(json) {
            json = $.parseJSON(json);
            if (json.ok) {
                $('#center-invite-list #send-at-'+id).text(json.send);
                $(resendid).html(' <span class="timeout">'+time+'</span>秒后可重发');
                $(timeoutid).text(time);
                if (_timeOut[id] == void(0)) {
                    _timeOut[id] = new Array();
                }
                _timeOut[id]['time'] = time;
                swal({title: '邀请码重发成功', text: '邀请码已重新发送到对方邮箱！', type: "success", confirmButtonText: "确定"}, function() {
                    timeout(id, resendid, timeoutid);
                });
            } else {
                $(resendid).html('重发失败');
                $(resendid).on('click.resendInviteCode', resendInviteCode);
            }
        });
        event.stopPropagation();
    }
    //清空当前分页缓存
    function cleanPageCatch() {
        var page = $('#center-invite-list .pagination > li.active').attr('data-page');
        if(page) {
            _pageCatch[parseInt(page)] = '';
        } else {
            _pageCatch[0] = '';
        }
    }
    //插入一条邀请记录
    function appendInviteRow(row) {
        var html = '<tr>'+
                '<td>'+row.email+'</td>'+
                '<td>'+row.code+'</td>'+
                '<td><lable class="label label-sm label-default">未注册</lable></td>'+
                '<td id="send-at-'+row.id+'">'+row.sendAt+'</td>'+
                '<td><button type="button" class="btn btn-sm btn-success" id="resend-'+row.id+'" data-id="'+row.id+'"><span class="timeout">'+_inviteReSendTimeOut+'</span>秒后可重发</button></td>'+
                '</tr>';
        $('#center-invite-list>table>tbody').prepend(html);
        timeout(row.id, '#resend-'+row.id, '#resend-'+row.id+'>.timeout');
    }
    //设置重发时间倒计时
    function setInviteTime(obj) {
        $.each(obj, function(id, v) {
            if (_timeOut[id] == void(0)) {
                _timeOut[id] = new Array();
            }
            _timeOut[id]['time'] = v;
            window.clearInterval(_timeOut[id]['interval']);
            if (v > 0) {
                timeout(id, '#resend-'+id, '#resend-'+id+'>.timeout');
            } else {
                //绑定重发邀请码按钮点击事件
                $('#center-invite-list #resend-'+id).on('click.resendInviteCode', resendInviteCode);
            }
        });
    }

    //重发邀请倒计时 id邀请码ID
    function timeout(id, res, out) {
        _timeOut[id]['interval'] = window.setInterval( function() {
            _timeOut[id]['time'] -= 1;
            if (_timeOut[id]['time'] >= 0) {
                $(out).text(_timeOut[id]['time']);
            };
            if (_timeOut[id]['time'] <= 0) {
                $(res).text('可重发');
                $(res).on('click.resendInviteCode', resendInviteCode);
                window.clearInterval(_timeOut[id]['interval']);
            };
        }, 1000);
    }

</script>
<?php JsBlock::end();?>