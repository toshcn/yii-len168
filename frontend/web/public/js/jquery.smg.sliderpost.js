/**
 * jQuery plugin smgSlidePost for shuimugua.com
 *
 * @author xiaohao <toshcn@foxmail.com>
 * @vesion 0.1.0
 * @times 2015-11
 */

;(function($, window) {
    /** @type {Object} 默认配置 */
    var defaults = {
        img: '.post-img',//图片所在层div
        post: '.post-text',//文章所在层div
        active: '.active',//当前显示的层
        item: '.item',//用作计数的类名
        imgIndex: 'data-img-index',//用作图片列表分类的 data名
        postIndex: 'data-post-index',//用作文章列表分类的 data名
        prevs: 'slide-prev',//上一个按钮的name
        nexts: 'slide-next',//下一个按钮的name
        interval: 5000,//轮播时间
    };
    /** @type object [(合并默认配置后的全局配置] */
    var setts = {};
    var _smgInterval;
    var methods = {
        init: function() {
            bind();
            startSlide();
        },
        play: function() {
            playSlide();
        },
        start: function() {
            startSlide();
        },
        stop: function() {
            stopSlide();
        },
        disrop: function() {
            clearInterval(_smgInterval);
            _smgInterval = null;
        },
    };
    $.fn.smgSlidePost = function() {
        var method = arguments[0];
        //如果方法存在, 保存到method变量以便使用
        if (methods[method]) {
            method = methods[method];
            arguments = Array.prototype.slice.call(arguments, 1);
            setts = defaults;
        } else if (typeof(method) == 'object' || !method) {
            //如果方法不存在，检验对象是否为一个对象（JSON对象）或者method方法没有被传入
            method = methods.init;
            setts = $.extend({}, defaults, arguments[0]);
        } else {
            //如果方法不存在或者参数没传入，则报出错误。需要调用的方法没有被正确调用
            $.error('Method ' + method + ' does not exits on jQuery.smgSlidePost');
            return this;
        }
        this.each(function() {
            $this = $(this);
            method.apply($this, arguments);
        });
        return this;
    }

    function bind() {
        bindNext();
        bindPrev();
        bindMouseenter();
        bindMouseleave();
    };

    function bindMouseenter() {
        $this.on('mouseenter.slideMounseEvent', function() {
            stopSlide();
        });
    };
    function bindMouseleave() {
        $this.on('mouseleave.slideMounseEvent', function() {
            startSlide();
        });
    };
    function startSlide() {
        var play_el = $.proxy(playSlide, this);
        if (setts.interval > 0) {
            _smgInterval = setInterval(play_el, setts.interval);
        };
    };
    function stopSlide() {
        clearInterval(_smgInterval);
        _smgInterval = null;
    };
    function bindNext() {
        $this.find('[name="'+setts.nexts+'"]').on('click.slideNextEvent', function() {
            var index = 0;
            var img = $(this).siblings().children(setts.img);
            var post = $(this).siblings().children(setts.post);
            var total = img.children(setts.item).length;
            var active = parseInt(img.children(setts.active).attr(setts.imgIndex));
            if (isNaN(active)) {
                return;
            } else if (active == 1 && total > 1) {
                index = active + 1;
            } else if (active == total && active > 1) {
                index = 1;
            } else {
                index = active + 1;
            }
            img.find('.active').removeClass('active');
            post.find('.active').removeClass('active');
            img.children('['+setts.imgIndex+'="'+index+'"]').addClass('active');
            post.children('['+setts.postIndex+'="'+index+'"]').addClass('active');
        });
    };
    function bindPrev() {
        $this.find('[name="'+setts.prevs+'"]').on('click.slideNextEvent', function() {
            var index = 0;
            var img = $(this).siblings().children(setts.img);
            var post = $(this).siblings().children(setts.post);
            var total = img.children(setts.item).length;
            var active = parseInt(img.children(setts.active).attr(setts.imgIndex));
            if (isNaN(active)) {
                return;
            } else if (active == 1 && total > 1) {
                index = total;
            } else if (active == total && active > 1) {
                index = total - 1;
            } else {
                index = active - 1;
            }
            img.find('.active').removeClass('active');
            post.find('.active').removeClass('active');
            img.children('['+setts.imgIndex+'="'+index+'"]').addClass('active');
            post.children('['+setts.postIndex+'="'+index+'"]').addClass('active');
        });
    };
    function playSlide() {
        $('[name="'+setts.nexts+'"]').trigger('click.slideNextEvent');
    }
})(jQuery, window);