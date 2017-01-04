<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

namespace common\assets\yuntheme;

use yii\web\AssetBundle;

/**
 * Yun+ UI Sweetalert 对话框资源包
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */
class YunSweetalertAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/public/';

    public $css = [
        'vendor/sweetalert/dist/sweetalert.css',
    ];
    public $js = [
        'vendor/sweetalert/dist/sweetalert.min.js',
    ];

    public $depends = [
        'common\assets\yuntheme\YunBaseAsset',
    ];
}
