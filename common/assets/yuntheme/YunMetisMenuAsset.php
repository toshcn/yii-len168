<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

namespace common\assets\yuntheme;

use yii\web\AssetBundle;

/**
 * Yun+ UI MetisMenu 资源包
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */
class YunMetisMenuAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/public/yuntheme/';

    public $css = [
        'vendor/metisMenu/dist/metisMenu.min.css',
    ];
    public $js = [
        'vendor/metisMenu/dist/metisMenu.min.js',
    ];

    public $depends = [
        'common\assets\yuntheme\YunBaseAsset',
    ];
}
