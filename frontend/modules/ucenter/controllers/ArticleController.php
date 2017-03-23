<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license
 */

namespace frontend\modules\ucenter\controllers;

use Yii;
use yii\web\Response;
use common\models\Posts;
use common\models\Comments;
use common\models\Replies;
use frontend\modules\ucenter\controllers\CommonController;

/**
 * 文章相关的控制器
 * Article controller
 *
 * @author Toshcn <toshcn@foxmail.com>
 * @since 0.1
 */
class ArticleController extends CommonController
{

    /**
     * 发表评论
     * @return [type] [description]
     */
    public function actionSendComment()
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        $postid = Yii::$app->getRequest()->post('pid');
        if (($msg = Comments::canComment($postid)) === true) {
            $send = Yii::$app->getRequest()->post('send');
            $send['postid'] = $postid;
            $model = new Comments();

            if ($model->addComment($send)) {
                return ['ok' => 1];
            }
        }
        return ['ok' => 0, 'msg' => $msg];
    }
    /**
     * 发表评论回复
     * @return [type] [description]
     */
    public function actionSendReply()
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        $reply = Yii::$app->getRequest()->post('reply', []);
        if (($msg = Replies::canReply($reply)) === true) {
            $model = new Replies();

            if ($model->addReply($reply)) {
                $reply = $model->attributes;
                $reply['iscomment'] = $model->iscomment;
                $reply['hp'] = $model->hp;
                unset($reply['content']);
                return [
                    'ok' => 1,
                    'reply' => $reply
                ];
            }
        }
        return ['ok' => 0, 'msg' => $msg];
    }
}
