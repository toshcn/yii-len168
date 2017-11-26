<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Timeago 时间美化资源包
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */
class TimeagoAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/public/';

    public $js = [
        'vendor/jquery-timeago/jquery.timeago.min.js',
        'vendor/jquery-timeago/locales/jquery.timeago.zh-CN.js',
    ];
}
