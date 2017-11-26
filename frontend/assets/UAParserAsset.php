<?php
/**
 * @link http://www.shuimugua.com/
 * @copyright Copyright (c) 2015 shuimugua.com
 * @license
 */
namespace frontend\assets;
use yii\web\AssetBundle;

/**
 * Uaparser UA字符串分析资源包
 *
 * @author Toshcn <toshcn@foxmail.com>
 * @since 0.1
*/
class UAParserAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/public/';
    public $js = [
        'vendor/ua-parser-js/dist/ua-parser.min.js'
    ];
}
