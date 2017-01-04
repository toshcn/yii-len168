<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * site 前端资源包
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */
class SiteAsset extends AssetBundle
{
    public $basePath = '@frontend/web';
    public $baseUrl = '@web';

    public $css = [
        'public/css/site.css',
    ];
    public $js = [
        'public/js/site.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'common\assets\AwesomeAsset',
        'frontend\assets\BasicAsset',
        'frontend\assets\TimeagoAsset',
        'frontend\assets\AnimateAsset',
    ];
}
