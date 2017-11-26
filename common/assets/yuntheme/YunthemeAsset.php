<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

namespace common\assets\yuntheme;

use yii\web\AssetBundle;

/**
 * Yun+ UI yuntheme 全部资源包
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */
class YunthemeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/public/yuntheme/';

    public $css = [
        'css/yuntheme.css',
    ];
    public $js = [
        'js/yuntheme.js',
    ];
    public $depends = [
        'common\assets\yuntheme\YunMetisMenuAsset',
        'common\assets\yuntheme\YunToastrAsset',
        'common\assets\yuntheme\YunJqueryGritterAsset',
        'common\assets\yuntheme\YunAnimateAsset',
        'common\assets\yuntheme\YunSlimscrollAsset',
        'common\assets\yuntheme\YunNestableAsset',
        'common\assets\yuntheme\YunIcheckAsset',
        'common\assets\yuntheme\YunCropperAsset',
    ];
}
