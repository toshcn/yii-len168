<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * 会员中心 修改头像资源
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */
class AvatarAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $depends = [
        'frontend\assets\CropperAsset',
    ];
}
