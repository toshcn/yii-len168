<?php
/**
 * basic主题资源
 *
 * @author xiaohao <toshcn@qq.com> http://www.len168.com
 * @version 0.1.0
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class BasicAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'public/css/basic.css',
    ];
    public $js = [
        'public/js/basic.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'common\assets\AwesomeAsset',
        'frontend\assets\SweetalertAsset',
    ];
}
