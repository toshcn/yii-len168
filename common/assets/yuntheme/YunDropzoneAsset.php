<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

namespace common\assets\yuntheme;

use yii\web\AssetBundle;

/**
 * Yun+ UI Dropzone 上传插件资源包
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */
class YunDropzoneAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/public/yuntheme/';
    public $css = [
        'vendor/dropzone/dist/min/dropzone.min.css',
    ];
    public $js = [
        'vendor/dropzone/dist/min/dropzone.min.js',
    ];
    public $depends = [
        'common\assets\yuntheme\YunBaseAsset',
    ];
}
