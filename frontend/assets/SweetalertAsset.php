<?php
/**
 * @link http://www.shuimugua.com/
 * @copyright Copyright (c) 2015 shuimugua.com
 * @license
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Sweetalert 对话框资源包
 *
 * @author Toshcn <toshcn@foxmail.com>
 * @since 0.1
*/
class SweetalertAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/';

    public $css = [
        'public/vendor/sweetalert/dist/sweetalert.css',
    ];
    public $js = [
        'public/vendor/sweetalert/dist/sweetalert.min.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}
