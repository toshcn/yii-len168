/**
 * site js for common
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */
;jQuery(function($) {
    $(document).ready( function() {
        $('.timeago').timeago();//时间美化
        //回到顶部
        $('#backtop').on('click.backtopEvent', function() {
            var speed=200;//滑动的速度
            $('body').animate({ scrollTop: 0 }, speed);
            return false;
        });
        //展示会员信息弹出小部件
        $('body').on('mouseenter.authorLayoutEvent', '.author-widget', function() {
            var $box = $(this).children('.author-info-box');
            var uid = $box.attr('data-author');
            $box.css({'display':'inline-table'});
            if (! _authorWidget[uid]) {
                $.get(_authorWidgetUrl, {"id": uid}, function(html) {
                    if (html) {
                        _authorWidget[uid] = html;
                        $box.html(html);
                    }
                });
            } else {
                $box.html(_authorWidget[uid]);
            }
        }).on('mouseleave.authorLayoutEvent', '.author-widget', function() {
            $(this).children('.author-info-box').hide('fast');
        });

        $('#login-widget-close').on('click.closeLoginWidget', function() {
            hideLoginWidget();
        });

        $('#login-widget-submit').on('click.loginWidget', submitLoginWidgetForm);


    });
    //加关注
    $('body').on('click.addFollower', '[name^="add-follower-"]', function() {
        if (isGuest()) {
            showLoginWidget();
        } else {
            var uid = $(this).attr('data-user');
            swal({
                title: "关注",
                text: "确定要关注Ta吗！",
                type: "success",
                showCancelButton: true,
                confirmButtonText: "确认",
                cancelButtonText: "取消",
                closeOnConfirm: false
            }, function() {
                addFollowerResult(uid);
                _authorWidget[uid] = '';
            });
        };
    });
    //取消关注
    $('body').on('click.removeFollower', '[name^="remove-follower-"]', function() {
        if (isGuest()) {
            showLoginWidget();
        } else {
            var uid = $(this).attr('data-user');
            swal({
                title: "取消关注",
                text: "确定要取消关注Ta吗！",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "确认",
                cancelButtonText: "取消",
                closeOnConfirm: false
            }, function() {
                removeFollowerResult(uid);
                _authorWidget[uid] = '';
            });
        };
    });
    //显示发纸条弹出框
    $('body').on('click.showMessageWidget', '[name="send-message-btn"]', function() {
        if (isGuest()) {
            showLoginWidget();
        } else {
            $('#message-at').text($(this).attr('data-at'));
            $('#message-to-user').val($(this).attr('data-user'));
            showMessageWidget('#message-widget');
        }
    });
    //关闭发纸条弹出框
    $('[name="message-widget-close"]').on('click.closeMessageWidget', function() {
        hideMessageWidget($(this).attr('data-close'));
    });
    $('#send-message-btn').on('click.sendMessage', sendMessage);
    /*var wh = $(window).height(); //浏览器当前窗口可视区域高度
    var dh = $(document).height(); //浏览器当前窗口文档的高度
    var dbh = $(document.body).height();//浏览器当前窗口文档body的高度
    var dboh = $(document.body).outerHeight(true);//浏览器当前窗口文档body的总高度 包括border padding margin
    var ww = $(window).width(); //浏览器当前窗口可视区域宽度
    var dw = $(document).width();//浏览器当前窗口文档对象宽度
    var dbw = $(document.body).width();//浏览器当前窗口文档body的高度
    var dbow = $(document.body).outerWidth(true);//浏览器当前窗口文档body的总宽度 包括border padding margin
    console.log([wh, dh, dbh, dboh, ww, dw, dbw, dbow])*/
});
function isGuest() {
    return _isGuest;
}
//显示登录小部件
function showLoginWidget() {
    var wh = $(window).height(); //浏览器当前窗口可视区域高度
    var dw = $(document).width();//浏览器当前窗口文档对象宽度
    $('#login-widget').show();
    $('#fade-layout').show();
    var w = $('#login-widget').width();
    var h = $('#login-widget').height();
    var top = wh > h ? (wh - h) / 2 : 0;//顶部浮动像素
    var left = dw > w ? (dw - w) / 2 : 0;//左边浮动像素
    console.log([top, left])
    $('#login-widget').css({"top": top, "left": left});
}
//隐藏登录小部件
function hideLoginWidget() {
    $('#login-widget').hide();
    $('#fade-layout').hide();
}
//显示纸条小部件
function showMessageWidget(id) {
    var wh = $(window).height(); //浏览器当前窗口可视区域高度
    var dw = $(document).width();//浏览器当前窗口文档对象宽度
    $(id).show();
    $('#fade-layout').show();
    var w = $(id).width();
    var h = $(id).height();
    var top = wh > h ? (wh - h) / 2 : 0;//顶部浮动像素
    var left = dw > w ? (dw - w) / 2 : 0;//左边浮动像素
    console.log([top, left])
    $(id).css({"top": top, "left": left});
}
//隐藏纸条小部件
function hideMessageWidget(id) {
    $(id).hide();
    $('#fade-layout').hide();
}
//设置OS, 浏览器
function uaparser(os, browser) {
    var parser = new UAParser();
    var o = parser.getOS();
    var b = parser.getBrowser();
    console.log(os);
    console.log(browser);
    $(os).val(o.name);
    $(browser).val(b.name +','+ b.major);
}
//ajax提交登录
function submitLoginWidgetForm() {
    if (checkedLoginWidgetForm()) {
        loginWidgetFormLoading();
        loginWidgetFormRemoveErr();
        uaparser('[name="LoginForm[os]"]', '[name="LoginForm[browser]"]');
        $.post($('#login-widget-form').prop('action'), $('#login-widget-form').serialize(), function(json) {
            json = $.parseJSON(json);
            if (json.ok) {
                window.location.reload();
            } else {
                $('#error-msg').text(json.error);
                loginWidgetFormHasErr();
                if (json.interval > 0) {
                    $res = $('#login-widget-submit');
                    $res.html('(<b class="times" id="times">'+json.interval+'</b>)秒后再登录');
                    //登录多次错误倒计时
                    $id = $('#login-widget-submit > #times');
                    var interval = window.setInterval( function() {
                        json.interval -= 1;
                        if (json.interval >= 0) {
                            $res.off('click.loginWidget');
                            $id.text(json.interval);
                        };
                        if (json.interval <= 0) {
                            window.clearInterval(interval);
                            $res.html('登录');
                            $('#error-msg').text('');
                            $res.on('click.loginWidget', submitLoginWidgetForm);
                        };
                    }, 1000);
                } else {
                    $('#login-widget-submit').html('登录');
                    $('#login-widget-submit').on('click.loginWidget', submitLoginWidgetForm);
                }
            }
        });
    }
}
//显示登录中动画样式
function loginWidgetFormLoading() {
    $('#login-widget-submit').off('click.loginWidget');
    $('#login-widget-submit').html('<i class="fa fa-spinner fa-spin"></i>');
}
//前端检查登录名和密码格式
function checkedLoginWidgetForm() {
    var $uname = $('[name="LoginForm[username]"]');
    var $pwd = $('[name="LoginForm[password]"]');
    if ($uname.val().length < 1) {
        $uname.parent().addClass('has-error');
        $uname.focus();
        return false;
    }console.log($pwd.val().length)
    if ($pwd.val().length < 6) {
        $pwd.parent().addClass('has-error');
        $pwd.focus();
        return false;
    }
    return true;
}
//添加登录错误信息提示样式
function loginWidgetFormHasErr() {
    $('[name="LoginForm[username]"]').parent().addClass('has-error');
    $('[name="LoginForm[password]"]').parent().addClass('has-error');
}
//移除登录错误信息提示样式
function loginWidgetFormRemoveErr() {
    $('[name="LoginForm[username]"]').parent().removeClass('has-error');
    $('[name="LoginForm[password]"]').parent().removeClass('has-error');
}
//加关注结果提示
function addFollowerResult(uid) {
    $.post(_addFollowerUrl, {"uid": uid, "_csrf": _csrf}, function(json) {
        json = $.parseJSON(json);
        if (json.ok == 1) {
            swal({title: "结果",
                text: "已成功关注！",
                type: "success",
                showCancelButton: false,
                confirmButtonText: "确认",
            });
            _authorWidget[uid] = '';
            var that = $('.js-uinfo-box [name="add-follower-'+uid+'"]')
            that.html('<i class="fa fa-minus"></i> 取消关注</button>');
            that.prop('name', 'remove-follower-'+uid);
        } else {
            swal({title: "结果",
                text: "关注失败，请稍后再试！",
                type: "error",
                showCancelButton: false,
                confirmButtonText: "确认",
            });
        }
    });
}
//取消关注结果提示
function removeFollowerResult(uid, that) {
    $.post(_removeFollowerUrl, {"uid": uid, "_csrf": _csrf}, function(json) {
        json = $.parseJSON(json);
        if (json.ok == 1) {
            swal({title: "成功",
                text: "已成功取消关注！",
                type: "success",
                showCancelButton: false,
                confirmButtonText: "确认",
            });
            _authorWidget[uid] = '';
            var that = $('.js-uinfo-box [name="remove-follower-'+uid+'"]');
            that.html('<i class="fa fa-plus"></i> 关注</button>');
            that.prop('name', 'add-follower-'+uid);
        } else {
            swal({title: "失败",
                text: "取消关注失败，请稍后再试！",
                type: "error",
                showCancelButton: false,
                confirmButtonText: "确认",
            });
        }
    });
}

function sendMessage() {
    if (! $('[name="message[content]"]').val()) {
        $('[name="message[content]"]').focus();
        return false;
    }
    $('#send-message-btn').off('click.sendMessage');
    $('#send-message-btn').html('<i class="fa fa-spinner fa-spin"></i>发送中');
    $.post($('#message-widget-form').prop('action'), $('#message-widget-form').serialize(), function(json) {
        if (json.ok) {
            swal({title: "成功",
                text: "信息已发送成功！",
                type: "success",
                showCancelButton: false,
                confirmButtonText: "确认",
            }, function() {
                $('[name="message[content]"]').val('');
                hideMessageWidget('#message-widget');
            });
        } else {
            swal({title: "失败",
                text: "信息发送失败！",
                type: "error",
                showCancelButton: false,
                confirmButtonText: "确认",
            });
        }
        $('#send-message-btn').html('发送');
        $('#send-message-btn').on('click.sendMessage', sendMessage);
    });
}