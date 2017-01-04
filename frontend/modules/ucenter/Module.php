<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license
 */



namespace frontend\modules\ucenter;

use Yii;

/**
 * frontend 前台会员中心模块
 *
 * @author Toshcn <toshcn@foxmail.com>
 * @since 1.0
 */
class Module extends \yii\base\Module
{
    /**
     * 模块初始化
     */
    public function init()
    {
        parent::init();
        //加载当前路径下模块的config.php配置文件
        Yii::configure($this, require(__DIR__ . '/config.php'));
    }

}