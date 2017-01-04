<?php
/**
 * @link http://www.shuimugua.com/
 * @copyright Copyright (c) 2015 shuimugua.com
 * @license
 */

namespace common\assets\yuntheme;

use yii\web\AssetBundle;

/**
 * Yun+ UI UAParser UA字符串分析资源包
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */
class YunUAParserAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/public/yuntheme/';
    public $js = [
        'vendor/ua-parser-js/dist/ua-parser.min.js'
    ];
    public $depends = [
        'common\assets\yuntheme\YunBaseAsset',
    ];
}
