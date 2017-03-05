<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * backend全部资源包
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */
class BackendAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/public/';

    public $css = [
        'css/site.css',
    ];
    public $js = [
        'js/site.js',
    ];
    public $depends = [
        'common\assets\yuntheme\YunNestableAsset',
    ];
}
