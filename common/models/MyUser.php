<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

namespace common\models;

use Yii;
use yii\web\User;
use yii\web\IdentityInterface;

class MyUser extends User
{
    /**
     * 会员登录
     * 登录后更新auth_key令牌, 同一帐号无法异地同时登录
     * @see   [[yii\web\User]]
     * @param IdentityInterface $identity the user identity (which should already be authenticated)
     * @param int $duration number of seconds that the user can remain in logged-in status, defaults to `0`
     * @return bool whether the user is logged in
     */
    public function login(IdentityInterface $identity, $duration = 0)
    {
        if ($this->beforeLogin($identity, false, $duration)) {
            $this->switchIdentity($identity, $duration);
            $id = $identity->getId();
            $ip = (string) Yii::$app->getRequest()->getUserIP();
            if ($this->enableSession) {
                $log = "User '$id' logged in from $ip with duration $duration.";
            } else {
                $log = "User '$id' logged in from $ip. Session not enabled.";
            }
            Yii::info($log, __METHOD__);

            $identity->generateAuthKey();
            $identity->save(false);

            Yii::$app->getSession()->set('USER_AUTH_KEY', $identity->auth_key);

            $this->afterLogin($identity, false, $duration);
        }
        return !$this->getIsGuest();
    }


    /**
     * 检查会员是否登录
     * 如果认证成功（$this->getIdentity() != null）
     * 再检测auth_key的值是否与session中的相同，若不同判断其为未登录
     * Returns a value indicating whether the user is a guest (not authenticated).
     * @return bool whether the current user is a guest.
     * @see getIdentity()
     */
    public function getIsGuest()
    {
        if ($identity = $this->getIdentity()) {
            return !$identity->validateAuthKey(Yii::$app->getSession()->get('USER_AUTH_KEY'));
        }

        return $this->getIdentity() === null;
    }
}
