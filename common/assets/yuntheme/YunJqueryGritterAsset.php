<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

namespace common\assets\yuntheme;

use yii\web\AssetBundle;

/**
 * Yun+ UI Gritter 提示对话 资源包
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */
class YunJqueryGritterAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/public/yuntheme/';
    public $css = [
        'vendor/jquery.gritter/css/jquery.gritter.css',
    ];
    public $js = [
        'vendor/jquery.gritter/js/jquery.gritter.min.js',
    ];

    public $depends = [
        'common\assets\yuntheme\YunBaseAsset',
    ];
}
