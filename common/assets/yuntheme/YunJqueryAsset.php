<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

namespace common\assets\yuntheme;

use yii\web\AssetBundle;

/**
 * Yun+ UI Jquery资源包
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */
class YunJqueryAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/public/yuntheme/';
    public $js = [
        //'vendor/jquery/dist/jquery.min.js',
    ];

     public $depends = [
        'yii\web\JqueryAsset'
        //'common\assets\yuntheme\YunJqueryAsset',
    ];
}
