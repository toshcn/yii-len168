<?php
/**
 * awesome字体资源
 *
 * @author xiaohao <toshcn@qq.com> http://www.len168.com
 * @version 0.1.0
 */

namespace common\assets;

use yii\web\AssetBundle;

class AwesomeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'public/font/font-awesome/css/font-awesome.css',
    ];
    public $js = [
    ];
    public $depends = [
    ];
}
