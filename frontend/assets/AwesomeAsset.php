<?php
/**
 * awesome字体资源
 *
 * @author xiaohao <toshcn@qq.com> http://www.len168.com
 * @version 0.1.0
 */

namespace frontend\assets;

use yii\web\AssetBundle;

class AwesomeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'public/vendor/font-awesome/css/font-awesome.min.css',
    ];
}
