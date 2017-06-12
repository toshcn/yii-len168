<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\HttpException;
use common\models\Posts;
use common\models\PostViews;

class HelpController extends Controller
{
    /**
     * index默认页
     * @return mixed
     */
    public function actionIndex($id = 0)
    {
        if ($id) {
            return $this->renderHelpPost($id);
        }
        return $this->redirect(['/help/how-to-register']);
    }

    /**
     * 关于本站
     * @return mixed
     */
    public function actionAboute()
    {
        return $this->renderHelpPost(Yii::$app->params['help.aboute']);
    }

    /**
     * 注册协议
     * @return mixed
     */
    public function actionRegistrationProtocol()
    {
        return $this->renderHelpPost(Yii::$app->params['help.registrationProtocol']);
    }

    /**
     * 注册帮助
     * @return mixed
     */
    public function actionHowToRegister()
    {
        return $this->renderHelpPost(Yii::$app->params['help.howToRegister']);
    }

    /**
     * render post html template
     * @param  integer $postid 文章id
     * @return mixed
     */
    private function renderHelpPost($postid)
    {
        $postid = (int) $postid;
        if (!$postid) {
            throw new HttpException(404, '您在访问的文章不存在!');
        }
        $cache = Yii::$app->cache;
        $post = $cache->get(__METHOD__ . 'helpCache' . $postid);

        if (!$post) {
            $model = new PostViews();
            $post = $model->getPostViewsByPost($postid)->asArray()->one();
            if (!$post) {
                throw new HttpException(404, '您在访问的文章不存在!');
            }
            $post['copyrightStr'] = Posts::transformPostCopyright($post['copyright']);

            // 当数据库字段发生变化时，该缓存失效
            $dependency = new \yii\caching\DbDependency([
                'sql' => 'SELECT MAX(updated_at) FROM {{%posts}} WHERE postid=' . (int) $postid
            ]);
            $cache->add(__METHOD__ . 'helpCache' . $postid, $post, Yii::$app->params['catch.time.help'], $dependency);
        }
        if ($post['status'] == Posts::STATUS_DRAFT && $post['user_id'] != Yii::$app->getUser()->getId()) {
            throw new HttpException(404, '您在访问的文章未发表或非公开!');
        } else if (!$post['isopen'] && $post['user_id'] != Yii::$app->getUser()->id) {
            throw new HttpException(404, '您在访问的文章未发表或非公开!');
        }

        return $this->render('howToRegister', [
            'postid' => $postid,
            'post' => $post
        ]);
    }
}
