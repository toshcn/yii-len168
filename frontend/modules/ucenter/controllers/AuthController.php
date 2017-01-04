<?php
/**
 * 使用yii-authclient集成第三方登录
 *
 * @author xiaohao <toshcn@qq.com> http://www.len168.com
 * @version 0.1.0
 */


namespace frontend\modules\ucenter\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use common\models\AuthForm;
use common\components\OAuthHandler;

/**
 * 第三方认证控制器
 */
class AuthController extends Controller
{
    public $layout = 'accountLayout';
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength' => 5,
                'maxLength' => 5
            ],
            'login' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    /**
     * 第三方登录回调方法
     * @param  interface $authclient [@see ClientInterface]
     * @return url redirct
     */
    public function onAuthSuccess($authclient)
    {
        $oauth = new OAuthHandler($authclient);
        $oauth->handle();

        if (Yii::$app->getSession()->getFlash('AUTH_CLIENT_LINK_OAUTH')) {
            Yii::$app->user->setReturnUrl(Url::to(['/ucenter/user/link-oauth']));

            return $this->goBack();
        } else if (Yii::$app->getUser()->isGuest) {
            return $this->redirect(['/ucenter/auth/link', 'type' => $authclient->getId()]);
        } else {
            return $this->goHome();
        }

        return $this->redirect(['/site/error']);
    }

    /**
     * 第三方法帐号注册绑定
     * @return [type] [description]
     */
    public function actionLink()
    {
        $error = Yii::$app->getSession()->getFlash('AUTH_CLIENT_ERROR');
        $attributes = Yii::$app->getSession()->get('AUTH_CLIENT_USERINFO');
        if ($attributes) {
            $model = new AuthForm();
            $model->username = $attributes['username'];
            $model->nickname = $attributes['username'];
            $model->password = '';
            $model->repassword = '';
            $model->email = $attributes['email'];
            $model->avatar = $attributes['avatar'];
            $model->source = $attributes['source'];
            $model->source_id = (string)$attributes['source_id'];

            if ($model->load(Yii::$app->getRequest()->post()) && ($user = $model->signup())) {
                if (Yii::$app->user->login($user)) {
                    return $this->goHome();
                }
            }
            $model->password = '';
            $model->repassword = '';
            return $this->render('signup', [
                'model' => $model
            ]);
        } else {
            return $this->render('error', [
                'error' => (array) $error,
            ]);
        }
    }
}
