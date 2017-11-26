<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Cropper图像编辑工具资源
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */
class CropperAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'public/vendor/cropper/dist/cropper.min.css',
    ];
    public $js = [
        'public/vendor/cropper/dist/cropper.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
