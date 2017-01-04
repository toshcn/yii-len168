<?php
/**
 * @link http://www.shuimugua.com/
 * @copyright Copyright (c) 2015 shuimugua.com
 * @license 
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * administrator login page backend 前端资源包
 * 
 * @author Toshcn <toshcn@foxmail.com>
 * @since 0.1
*/
class LoginAsset extends AssetBundle
{
    public $sourcePath = '@frontend/web';
    public $css = [
        'public/css/smg.css',
    ];
    public $js = [
        'public/js/login.js',
    ];
    public $depends = [
        'common\assets\yun\YunJqueryAsset',
        'common\assets\yun\YunBootstrapAsset',
        'common\assets\yun\YunFontAwesomeAsset',
        'common\assets\yun\YunAnimateAsset',
    ];
}
