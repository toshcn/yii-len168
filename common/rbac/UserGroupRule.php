<?php
/**
 * 用户组规则
 *
 * @author toshcn <toshcn@qq.com> http://www.len168.com
 * @version 0.1.0
 */

namespace common\rbac;

use Yii;
use yii\rbac\Rule;

/**
 * 检查是否匹配用户的组
 */
class UserGroupRule extends Rule
{
    public $name = 'userGroup';

    public function execute($user, $item, $params)
    {
        if (!Yii::$app->user->isGuest) {
            $group = Yii::$app->user->identity->group;
            if ($item->name === 'admin') {
                return $group == 10;
            } elseif ($item->name === 'author') {
                return $group == 10 || $group == 20;
            }
        }
        return false;
    }
}
