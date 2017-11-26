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
use common\models\PostAttributes;
use common\models\User;
use common\models\Comments;

/**
 * ajax request controller
 */
class AjaxController extends Controller
{
    public $layout = false;
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
        ];
    }
    /**
     * 会员资料小部件
     * @return [type] [description]
     */
    public function actionAuthorWidget()
    {
        if ($uid = intval(Yii::$app->getRequest()->get('id'))) {
            $cache = Yii::$app->cache;
            $author = $cache->get(__METHOD__ . 'authorWidgetCache' . $uid);
            if (!$author) {
                $model = new User();
                $author = $model->getAuthorWidget($uid);
                $cache->add(__METHOD__ . 'authorWidgetCache' . $uid, $author, Yii::$app->params['catch.time.authorWidget']);
            }
            if ($author) {
                return $this->renderAjax('_authorWidget', [
                    'author' => $author,
                    'myself' => Yii::$app->user->getId()
                ]);
            }
        }
        return;
    }
    //文章评论
    public function actionArticleComment()
    {
        $request = Yii::$app->getRequest();
        $postid  = intval($request->get('id', 0));
        $page    = intval($request->get('page', 0));
        $order   = (string) $request->get('sort', 'asc');

        $query  = Posts::findOne($postid)->getComments();
        $count  = $query->count();

        if ($count && in_array($order, ['asc', 'desc'])) {
            $sort = '{{%comments}}.commentid ASC';
            if ($order === 'desc') {
                $sort = '{{%comments}}.commentid DESC';
            }
            $pagination = new Pagination([
                'totalCount'    => $count,
                'pageSizeParam' => false,
                'params'        => ['page' => $request->get('page'), 'id' => $postid , 'sort' => $order],
            ]);
            $pagination->setPageSize(10);

            $comments = $query
                ->orderBy($sort)
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->asArray()
                ->all();

            return $this->render('_articleComment', [
                'comments' => $comments,
                'pagination' => $pagination,
            ]);
        }
        return null;
    }
    /**
     * 获取评论的回复
     * @return [type] [description]
     */
    public function actionArticleCommentReply()
    {
        $request   = Yii::$app->getRequest();
        $commentid = intval($request->get('commid', 0));
        $page      = intval($request->get('page', 0));
        $where     = trim($request->get('where', 'comment'));
        if ($commentid && in_array($where, ['comment', 'my'])) {
            $query  = Comments::findOne($commentid)->getReplies();
            $counts  = $query->count();
            $pagination = new Pagination([
                'totalCount'    => $counts,
                'pageSizeParam' => false,
            ]);
            $pagination->setPageSize(Yii::$app->params['replyPageSize']);
            $pagination->setPage($page);
            $replies = $query->offset($pagination->offset)
                ->limit($pagination->limit)
                ->asArray()
                ->all();
            $html = $this->render('_articleCommentReply', [
                'replies' => $replies,
                'commentid' => $commentid,
                'where' => $where,
                'index' => $page * Yii::$app->params['replyPageSize'] + 1,
            ]);
            return json_encode(['html' => $html, 'rows' => $counts, 'size' => count($replies)], true);
        }
        return json_encode(['html' => '']);
    }
    /**
     * 增加文章浏览量
     * @return [type] [description]
     */
    public function actionIncreaseViews()
    {
        if ($postid = Yii::$app->getRequest()->get('id', 0)) {
            PostAttributes::increaseViews($postid);
        }
        return;
    }
}
