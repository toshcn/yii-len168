<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * site controller signup 前端资源包
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */
class SignupAsset extends AssetBundle
{
    public $basePath = '@frontend/web';
    public $baseUrl = '@web';

    public $depends = [
        'frontend\assets\SiteAsset',
        'frontend\assets\UAParserAsset',
    ];
}
