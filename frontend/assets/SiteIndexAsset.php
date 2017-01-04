<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * site 首页前端资源包
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */
class SiteIndexAsset extends AssetBundle
{
    public $basePath = '@frontend/web';
    public $baseUrl = '@web';
    public $js = [
        'public/js/jquery.smg.sliderpost.js',
        'public/js/index.js',
    ];
    public $depends = [
        'frontend\assets\SiteAsset',
    ];
}
