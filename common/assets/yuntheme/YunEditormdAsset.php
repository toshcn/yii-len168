<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

namespace common\assets\yuntheme;

use yii\web\AssetBundle;

/**
 * editormd 编辑器资源包
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */
class YunEditormdAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/public/yuntheme/';
    public $css = [
        'vendor/editor.md/css/editormd.min.css',
        'vendor/editor.md/css/editormd.preview.min.css',
    ];
    public $js = [
        'vendor/editor.md/editormd.min.js',
    ];
    public $depends = [
        'common\assets\yuntheme\YunBaseAsset',
    ];
}
