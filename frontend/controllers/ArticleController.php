<?php
/**
 * @link http://www.shuimugua.com/
 * @copyright Copyright (c) 2015 shuimugua.com
 * @license
 */
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\Controller;
use yii\data\Pagination;
use common\models\PostViews;
use common\models\Posts;
use common\models\PostAttributes;
use common\models\Comments;
use common\models\Replies;
use common\models\Terms;

/**
 * 文章相关的控制器
 * Article controller
 *
 * @author Toshcn <toshcn@foxmail.com>
 * @since 0.1
 */
class ArticleController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\PageCache',
                'only' => ['lists'],
                'duration' => 300,
                'variations' => [
                    \Yii::$app->language,
                ]
            ],
        ];
    }


    /**
     * 独立控制器
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
            ],
        ];
    }

    /**
     * 分类列表
     */
    public function actionLists()
    {
        $id = Yii::$app->getRequest()->get('id', 0);
        $query = Terms::findOne(['termid' => $id])
                ->getNicePostViews()
                ->select([
                    'postid', 'user_id', 'title', 'head', 'posttype', 'author', 'nickname', 'image', 'image_suffix', 'description', 'views', 'comments', 'created_at'
                ]);

        $pagination = new Pagination([
            'totalCount' => $query->count(),
            'pageSizeParam' => false,
            'pageSize' => Yii::$app->params['post.pageSize']
        ]);

        $posts = $query
            ->orderBy(['postid' => SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $this->render('lists', [
            'posts' => $posts,
            'categorys' => Terms::getCategorys($id)->asArray()->all(),
            'pagination' => $pagination
        ]);
    }

    /**
     * 文章阅读页
     * @return [type] [description]
     */
    public function actionDetail()
    {
        $cache = Yii::$app->cache;
        $uid = Yii::$app->user->getId();
        $postid = (int) Yii::$app->getRequest()->get('id', 0);
        $post = $cache->get(__METHOD__ . 'postCache' . $postid);

        if (!$post) {
            $model = new PostViews();
            $post = $model->getPostViewsByPost($postid)->asArray()->one();
            // 当数据库字段发生变化时，该缓存失效
            $dependency = new \yii\caching\DbDependency(
                ['sql' => 'SELECT MAX(updated_at) FROM {{%posts}} WHERE postid=' . (int) $postid]
            );
            $cache->add(__METHOD__ . 'postCache' . $postid, $post, Yii::$app->params['catch.time.post'], $dependency);
            if (!$post) {
                throw new HttpException(404, '您在访问的文章不存在!');
            }
        }
        if ($post['status'] == Posts::STATUS_DRAFT && $post['user_id'] != Yii::$app->getUser()->getId()) {
            throw new HttpException(404, '您在访问的文章未发表或非公开!');
        } else if (!$post['isopen'] && $post['user_id'] != $uid) {
            throw new HttpException(404, '您在访问的文章未发表或非公开!');
        }

        $post['typeStr'] = Posts::transformPostType($post['posttype']);
        $post['copyrightStr'] = Posts::transformPostCopyright($post['copyright']);
        $post['progress'] = $post['hp'] < PostAttributes::DEFAULT_HP ? $post['hp'] / PostAttributes::DEFAULT_HP * 100 : 100;

        $myComment = [];
        if (!Yii::$app->user->isGuest) {
            if (!($myComment = $cache->get(__METHOD__ . 'userComment-'.$postid.$uid))) {
                $comment = new Comments();
                $myComment = $comment->getUserComment($postid, $uid)->asArray()->one();
                if ($myComment) {
                    $myComment['progress'] = $myComment['hp'] < Comments::DEFAULT_HP ? $myComment['hp'] / Comments::DEFAULT_HP * 100 : 100;
                    $myComment['standStr'] = $comment->transformCommentStand($myComment['stand']);
                    $u = Yii::$app->user->identity->attributes;
                    $myComment['user'] = array();
                    $myComment['user']['uid'] = $u['uid'];
                    $myComment['user']['nickname'] = $u['nickname'];
                    $myComment['user']['sex'] = $u['sex'];
                    $myComment['user']['head'] = $u['head'];
                    $myComment['user']['isauth'] = $u['isauth'];
                }
                $dep = new \yii\caching\DbDependency(
                    ['sql' => 'SELECT count(*) FROM {{%replies}} WHERE comment_id=' . (int) $myComment['commentid']]
                );
                $cache->add(__METHOD__ . 'userComment-'.$postid.$uid, $myComment, Yii::$app->params['catch.time.userComment'], $dep);
            }
        }

        return $this->render('detail', [
            'postid' => $postid,
            'post' => $post,
            'myComment' => $myComment
        ]);
    }
}
