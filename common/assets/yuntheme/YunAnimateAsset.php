<?php
/**
 * @link http://www.shuimugua.com/
 * @copyright Copyright (c) 2015 shuimugua.com
 * @license
 */

namespace common\assets\yuntheme;

use yii\web\AssetBundle;

/**
 * Yun+ UI Animate 动画资源包
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */
class YunAnimateAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/public/yuntheme/';
    public $css = [
        'vendor/animate.css/animate.min.css',
    ];
}
