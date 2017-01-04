<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license
 */



namespace frontend\modules\ucenter\controllers;

use Yii;
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
        $postid = Yii::$app->getRequest()->post('pid');
        if ($postid) {
            $send = Yii::$app->getRequest()->post('send');
            $send['postid'] = $postid;
            $model = new Comments();

            if ($model->addComment($send)) {
                return json_encode(['ok' => 1]);
            }
        }
        return json_encode(['ok' => 0]);
    }
    /**
     * 发表评论回复
     * @return [type] [description]
     */
    public function actionSendReply()
    {
        $reply = Yii::$app->getRequest()->post('reply');
        if ($reply) {
            $model = new Replies();

            if ($model->addReply($reply)) {
                $reply = $model->attributes;
                $reply['iscomment'] = $model->iscomment;
                $reply['hp'] = $model->hp;
                unset($reply['content']);
                return json_encode([
                    'ok' => 1,
                    'reply' => $reply
                ]);
            }
        }
        return json_encode(['ok' => 0]);
    }



}
