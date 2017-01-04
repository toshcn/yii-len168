<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * article 控制器资源包
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */
class ArticleAsset extends AssetBundle
{
    public $basePath = '@frontend/web';
    public $baseUrl = '@web';

    public $js = [
        'public/js/article.js',
    ];
    public $depends = [
        'frontend\assets\EditormdAsset',
    ];
}
