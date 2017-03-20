<?php
/**
 * @link http://www.shuimugua.com/
 * @copyright Copyright (c) 2015 shuimugua.com
 * @license
 */

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use common\models\Posts;
use common\models\PostViews;
use common\models\Terms;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
        ];
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
                'transparent' => true,
                'minLength' => 5,
                'maxLength' => 5,
            ],
        ];
    }

    /**
     * 首页
     */
    public function actionIndex()
    {
        $post = new PostViews();

        $query = $post->getNicePostViews()
            ->select(['postid', 'user_id', 'title', 'head', 'posttype', 'author', 'nickname', 'image', 'image_suffix', 'description', 'views', 'comments', 'created_at']);

        $pagination = new Pagination([
            'totalCount' => $query->count(),
            'pageSizeParam' => false,
            'pageSize' => Yii::$app->params['post.pageSize']
        ]);

        $views = $query
            ->orderBy(['postid' => SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $this->render('index', [
            'topPosts' => $post->getTopPosts()->asArray()->all(),
            'categorys' => Terms::getCategorys()->asArray()->all(),
            'posts' => $views,
            'pagination' => $pagination
        ]);
    }
    /**
     * 退出登录 logout
     * @return redirect to home page
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        $referer = Yii::$app->getRequest()->headers->get('Referer');
        if (strpos($referer, 'user/center')) {
            return $this->goHome();
        }
        Yii::$app->user->setReturnUrl(Yii::$app->getRequest()->headers->get('Referer'));
        return $this->goBack();
    }

    public function actionAbout()
    {
        $this->layout = false;
        return $this->render('about');
    }
}
