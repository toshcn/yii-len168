<?php
/**
 * @link http://www.shuimugua.com/
 * @copyright Copyright (c) 2015 shuimugua.com
 * @license 
 * 
 */

namespace common\widgets;

use yii\web\View;
use yii\widgets\Block;

/**
 * 页面注入JavaScript 小部件JsBlock
 * 使用方法，在视图页面中加入Js代码
 * ~~~
 * <?php \common\widgets\JsBlock::begin() ?>
 *   <script>
 *       //code...
 *   </script>
 * <?php \common\widgets\JsBlock::end() ?>
 * ~~~
 * @author xiaohao <toshcn@foxmail.com>
 * @version 0.1
 */
class JsBlock extends Block
{
    /**
     * @var string $key the key that identifies the JS code block. If null, it will use
     * $js as the key. If two JS code blocks are registered with the same key, the latter
     * will overwrite the former.
     * 如果设置了此值当前页面使用两个以上此小部件时，此键作为JS代码块的标识字符。
     * 如果设为空值，默认使用$js作为标识，使用多个此小部件时最后一个部件会覆盖前面的部件。
     */
    public $key = null;

    /**
     * @var integer $pos Js代码块插入页面的位置标识
     * - [[POS_HEAD]]: 在head标签
     * - [[POS_BEGIN]]: 在body标签开始前
     * - [[POS_END]]: 在body标签结束前
     * - [[POS_LOAD]]: 在jQuery(window).load().
     */
    public $pos = View::POS_END;

    public function run()
    {
        $content = ob_get_clean();
        if ($this->renderInPlace) {
           throw new \Exception("not implemented yet ! ");
           // echo $content;
        }
        $content = trim($content);
        $jsBlockPattern  = '|^<script[^>]*>(?P<block_content>.+?)</script>$|is';
        if(preg_match($jsBlockPattern, $content, $matches)){
            $content =  $matches['block_content'];
        }

        $this->view->registerJs($content, $this->pos, $this->key);
    }
}