/**
 * basic js dependent jQuery
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */

;(function($) {
    $(document).ready(function() {
        //监听滚动时事
        $(window).scroll(function() {
            var scrollHeight = $(document).scrollTop();
            if (scrollHeight > 100) {
                $('#backtop-box').show();
            } else {
                $('#backtop-box').hide();
            }
        });
    });
})(window.jQuery);