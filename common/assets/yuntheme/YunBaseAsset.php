<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

namespace common\assets\yuntheme;

use yii\web\AssetBundle;

/**
 * Yun+ UI base 基础资源包
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */
class YunBaseAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/public/yuntheme/';

    public $depends = [
        'common\assets\yuntheme\YunJqueryAsset',
        'common\assets\yuntheme\YunBootstrapAsset',
        'common\assets\yuntheme\YunFontAwesomeAsset',
    ];
}
