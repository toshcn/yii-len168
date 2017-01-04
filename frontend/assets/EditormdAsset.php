<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Editormd资源包
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */
class EditormdAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/public';

    public $css = [
        'vendor/editor.md/css/editormd.min.css',
        'vendor/editor.md/css/editormd.preview.min.css',
        'vendor/editor.md/lib/codemirror/codemirror.min.css',
        'vendor/editor.md/lib/codemirror/addon/dialog/dialog.css',
        'vendor/editor.md/lib/codemirror/addon/search/matchesonscrollbar.css',
        'vendor/editor.md/lib/codemirror/addon/fold/foldgutter.css',
    ];
    public $js = [
        'vendor/editor.md/editormd.min.js',
        'vendor/editor.md/lib/marked.min.js',
        'vendor/editor.md/lib/flowchart.min.js',
        'vendor/editor.md/lib/jquery.flowchart.min.js',
        'vendor/editor.md/lib/prettify.min.js',
        'vendor/editor.md/lib/raphael.min.js',
        'vendor/editor.md/lib/underscore.min.js',
        'vendor/editor.md/lib/codemirror/codemirror.min.js',
        'vendor/editor.md/lib/codemirror/addons.min.js',
        'vendor/editor.md/lib/sequence-diagram.min.js',
    ];

    public $depends = [
        'frontend\assets\SiteAsset',
    ];
}
