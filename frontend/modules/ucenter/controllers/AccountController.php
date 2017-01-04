<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license
 */


namespace frontend\modules\ucenter\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\base\InvalidParamException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\models\LoginForm;
use frontend\modules\ucenter\models\ForgetPasswordForm;
use frontend\modules\ucenter\models\ResetPasswordForm;
use frontend\modules\ucenter\models\SignupForm;

/**
 * 前台会员 模块 帐户控制器（登录/注册）非登录用户可用
 * frontend module account controller for user login or signup.
 *
 * @author Toshcn <toshcn@foxmail.com>
 * @since 0.1
*/
class AccountController extends Controller
{
    public $layout = 'accountLayout';
    /**
     * 登录用户跳转到会员中心
     * urlManager组件必需配置
     * ```
     * [
     *     'enablePrettyUrl' = true,
     *     'showScriptName' => false,
     * ]
     * ```
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (!\Yii::$app->user->isGuest) {
                return $this->redirect(['/ucenter/user/index']);
            }

            return true;
        }
        return false;
    }
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [];
    }
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                //'transparent' => true,
                'minLength' => 5,
                'maxLength' => 5,
            ],
        ];
    }

    public function actionIndex()
    {
        $this->redirect(['login']);
    }
    /**
     * 正常登录
     * @return [type] [description]
     */
    public function actionLogin()
    {

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->getRequest()->post()) && $model->login()) {
            return $this->goBack();
        } else {
            Yii::$app->user->setReturnUrl(Yii::$app->getRequest()->headers->get('Referer'));
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
    /**
     * ajax方式登录
     * @return [type] [description]
     */
    public function actionAjaxLogin()
    {
        if (Yii::$app->user->isGuest) {
            $model = new LoginForm();
            if ($model->load(Yii::$app->getRequest()->post()) && $model->login()) {
                return json_encode(['ok' => 1]);
            }
            return json_encode([
                'ok' => 0,
                'error' => $model->tipes ? $model->tipes : '用户名或密码错误',
                'interval' => $model->interval,
            ]);
        }
        return json_encode(['ok' => 1]);
    }

    /**
     * 会员注册 member register
     */
    public function actionSignup()
    {
        $this->layout = '/main';
        $model = new SignupForm();
        $request = Yii::$app->getRequest();
        if ($model->load($request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goBack();
                }
            }
        }

        Yii::$app->user->setReturnUrl($request->headers->get('Referer'));
        $model->inviteCode = $request->get('code');
        $model->password = '';
        $model->repassword = '';
        return $this->render('signup', [
            'model' => $model,
        ]);
    }
    /**
     * 忘记密码
     * @return [type] [description]
     */
    public function actionForgetPassword()
    {
        $model = new ForgetPasswordForm();
        /* @var $issend 是否已经发送过重置密码链接  */
        $issend = 0;
        /** @var integer 发送邮件成功 */
        $success = 0;
        /** @var integer 离可重发时间还有N秒 */
        $expires = 0;

        if (Yii::$app->getRequest()->isPost && $model->load(Yii::$app->getRequest()->post()) && $model->validate()) {
            if (!empty($model->getUser()->reset_token)) {
                $expires = Yii::$app->params['resendResetTokenExpire'] * 60 - (Yii::$app->params['resetTokenExpire'] * 60 - (strtotime($model->getUser()->reset_token_expire) - time()));
                $issend = $expires > 0 ? 1 : 0;
            }

            if (!$issend && $model->sendEmail()) {
                $success = 1;
            }
        }

        return $this->render('forgetPassword', [
            'model' => $model,
            'issend' => $issend,
            'success' => $success,
            'expires' => $expires,
        ]);
    }

    /**
     * 重置密码
     * @param  string $token 重置密码令牌
     * @param  integer $user  user id
     */
    public function actionResetPassword($token, $user)
    {
        try {
            $model = new ResetPasswordForm($token, $user);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->getRequest()->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('ResetPasswordSuccess', 1);
            Yii::$app->getSession()->setFlash('ResetPasswordToken', $model->getToken());
            Yii::$app->getSession()->setFlash('ResetPasswordUser', $model->getUser()->uid);

            return $this->redirect(['/ucenter/account/reset-password-success']);
        } else {
            return $this->render('resetPassword', [
                'model' => $model,
            ]);
        }
    }

    /**
     * 重置密码成功页
     * @return [type] [description]
     */
    public function actionResetPasswordSuccess()
    {
        if (Yii::$app->getSession()->getFlash('ResetPasswordSuccess')) {
            return $this->render('resetPasswordSuccess');
        } else {
            return $this->redirect(['/ucenter/account/forget-password']);
        }
    }
}
