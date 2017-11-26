<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

namespace common\assets\yuntheme;

use yii\web\AssetBundle;

/**
 * Yun+ UI Timeago 时间美化资源包
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */
class YunTimeagoAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/public/yuntheme/';

    public $js = [
        'vendor/jquery-timeago/jquery.timeago.js',
        'vendor/jquery-timeago/locales/jquery.timeago.zh-CN.js',
    ];
    public $depends = [
        'common\assets\yuntheme\YunBaseAsset',
    ];
}
