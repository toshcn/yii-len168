<?php
/**
 * @link http://www.shuimugua.com/
 * @copyright Copyright (c) 2015 shuimugua.com
 * @license
 *
 */

namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\User;
use common\models\UserLogin;
use common\models\UserInfo;

/**
 * Application initialization console controllers to create Administrator and something.
 * 命令行 初始化控制器，初始化创建管理员帐号和一些其它初始化的事情。
 *
 * @author xiaohao <toshcn@foxmail.com>
 * @since 0.1
 */
class InitController extends Controller
{
    /**
     * 初始化路由
     * @return [type] [description]
     */
    public function actionCreateMenu()
    {
        echo "创建菜单 ...\n"; // comment 提示当前操作
        $auth = Yii::$app->authManager;
        foreach ($this->routes as $key => $value) {
            if ($auth->getItem($value) === null) {
                $createPost = $auth->createPermission($value);
                $auth->add($createPost);
            }
        }
        return self::EXIT_CODE_NORMAL;
    }

    /**
     * createAdministrator 创建超级管理员帐号
     * @return [type] [description]
     */
    public function actionCreateAdministrator()
    {
        echo "Create an administrator ...\n"; // comment 提示当前操作
        $uid = (int) $this->prompt('Administrator userid:'); // 接收用户id
        $username = $this->prompt('Administrator username:'); // 接收用户名
        do {
            $email = $this->prompt('Administrator email:'); // 接收Email
        } while (!filter_var($email, FILTER_VALIDATE_EMAIL));
        $password = trim($this->prompt('Administrator password:')); // 接收密码
        if ($uid && $this->confirm("Are your sure to create an Administrator?")) {
            //启用事务
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $user = new User(['scenario' => 'signup']);
                $user->uid = $uid;
                $user->username = $username;
                $user->nickname = $username;
                $user->group    = Yii::$app->params['user.administratorRoleGroupId'];
                $user->email    = $email;
                $user->head     = Yii::$app->params['user.defaultAvatar'];
                $user->setPassword($password);
                $user->generateAuthKey();
                $user->created_at = date('Y-m-d H:i:s');
                $user->updated_at = $user->created_at;
                if ($user->save()) {
                    //添加到会员登录表
                    $login           = new UserLogin();
                    $login->user_id  = $user->uid;
                    $login->login_at = $user->created_at;
                    //添加到会员个人资料表
                    $userInfo      = new UserInfo();
                    $userInfo->user_id = $user->uid;
                    $userInfo->realname = $user->username;
                    $userInfo->birthday = date('Y-m-d');
                    $userInfo->created_at = $user->created_at;
                    $userInfo->updated_at = $user->created_at;
                    //必需同时写入会员登录表，会员资料表，会员财富表才会注册成功
                    if ($login->save(false) && $userInfo->save(false)) {
                        $auth = Yii::$app->getAuthManager();
                        if ($auth->getAssignment('admin', $user->uid) === null) {
                            $role = $auth->getRole('admin');
                            $auth->assign($role, $user->uid);
                        }
                        $transaction->commit();//提交事务
                        echo "成功创建超级管理员帐号\n";
                        return self::EXIT_CODE_NORMAL;
                    }
                } else {
                    foreach ($user->getErrors() as $error) {
                        foreach ($error as $e) {
                            echo "$e\n";
                        }
                    }
                    $transaction->rollback();//事务回滚
                    return self::EXIT_CODE_ERROR;
                }
            } catch (Exception $e) {
                echo "错误：$e\n";
                $transaction->rollback();//事务回滚
                return self::EXIT_CODE_ERROR;
            }
        }
        echo "已取消 \n";
        return self::EXIT_CODE_NORMAL;
    }


    private function routes()
    {
        return [
            '/auth/*',
            '/auth/error',
            '/auth/index',
            '/auth/login',
            '/auth/logout',
            '/link/*',
            '/link/ajax-search-links',
            '/link/category',
            '/link/create',
            '/link/delete',
            '/link/error',
            '/link/index',
            '/link/update',
            '/link/update-category',
            '/link/view',
            '/main/*',
            '/main/error',
            '/post/*',
            '/post/ajax-lock',
            '/post/ajax-nice',
            '/post/ajax-search-posts',
            '/post/ajax-stick',
            '/post/category',
            '/post/delete-category',
            '/post/error',
            '/post/index',
            '/post/update-category',
            '/site/*',
            '/site/error',
            '/site/index',
            '/site/login',
            '/site/logout',
            '/surface/*',
            '/surface/ajax-create-menu',
            '/surface/ajax-search-cates',
            '/surface/edit-nav-menu',
            '/surface/error',
            '/surface/index',
            '/surface/menus',
            '/system/*',
            '/system/error',
            '/system/general',
            '/system/index',
            '/user/*',
            '/user/ajax-auth',
            '/user/ajax-status',
            '/user/error',
            '/user/index',
            '/user/login',
            '/user/logout',
            '/user/view',
        ];
    }
}
