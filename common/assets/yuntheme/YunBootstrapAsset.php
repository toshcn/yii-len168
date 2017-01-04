<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

namespace common\assets\yuntheme;

use yii\web\AssetBundle;

/**
 * Yun+ UI Bootstrap资源包
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */
class YunBootstrapAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/public/yuntheme/';
    public $css = [
        //'vendor/bootstrap/dist/css/bootstrap.min.css',
    ];
    public $js = [
        //'vendor/bootstrap/dist/js/bootstrap.min.js',
    ];

    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset'
        //'common\assets\yuntheme\YunJqueryAsset',
    ];
}
