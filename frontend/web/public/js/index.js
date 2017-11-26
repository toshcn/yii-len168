//首页内联js
;jQuery(function($) {
    $(document).ready( function() {
        //监听滚动时事
        $(window).scroll(function() {
            var scrollHeight = $(document).scrollTop();
            runPostFlash($(window).height(), scrollHeight);
        });
        runPostFlash($(window).height(), $(document).scrollTop());
        $('.slide-post').smgSlidePost();
    });
    //文章列表延时显示
    function runPostFlash(screenHeight, scrollHeight) {
        var postInterval = setInterval(
            function () {
                var that = $('.post-unview').first();
                if (that.hasClass('post-unview') && screenHeight + scrollHeight - that.offset().top > 0) {
                    that.removeClass('post-unview');
                } else {
                    clearInterval(postInterval);
                }
            }, 250);
    };
});