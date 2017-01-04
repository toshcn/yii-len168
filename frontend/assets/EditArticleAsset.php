<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * article 编辑文章资源包
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */
class EditArticleAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/public';

    public $js = [
        'js/editArticle.js'
    ];

    public $depends = [
        'frontend\assets\EditormdAsset',
        'frontend\assets\CropperAsset',
        'frontend\assets\ChosenAsset',
        'frontend\assets\ToastrAsset',
    ];
}
