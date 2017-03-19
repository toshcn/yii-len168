<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * blueimp资源
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */
class JqueryBlueimpAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'public/vendor/blueimp-gallery/css/blueimp-gallery.min.css',
    ];

    public $js = [
        'public/vendor/blueimp-gallery/js/jquery.blueimp-gallery.min.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
