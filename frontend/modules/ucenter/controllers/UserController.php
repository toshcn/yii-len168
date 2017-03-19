<?php
/**
 * @link http://www.shuimugua.com/
 * @copyright Copyright (c) 2015 shuimugua.com
 * @license
 */

namespace frontend\modules\ucenter\controllers;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\Response;
use yii\data\Pagination;
use common\models\Terms;
use common\models\PostViews;
use common\models\Invites;
use common\models\InviteForm;
use common\models\Followers;
use common\models\Messages;
use common\models\User;
use common\models\Auth;
use frontend\modules\ucenter\models\UserInfoForm;
use frontend\modules\ucenter\controllers\CommonController;

/**
 * frontend 会员模块 会员控制器
 *
 * @author toshcn <toshcn@foxmail.com>
 * @since 0.1.0
*/
class UserController extends CommonController
{
    /**
     * 会员个人主页
     * @return [type] [description]
     */
    public function actionIndex()
    {
        return $this->redirect(['/ucenter/user/posts']);
    }
    /**
     * 个人信息
     * @return [type] [description]
     */
    public function actionDetail()
    {
        $me  = Yii::$app->getUser()->getId();
        $uid = intval(Yii::$app->getRequest()->get('uid', $me));

        $userDetail = User::getUserDetailWithInfo($uid);
        $view = 'detail';
        if ($uid === $me) {
            $view = 'selfDetail';
        } else {
            $userDetail['isfollower'] = Followers::getFollowerStatus([$uid, $me]);
        }
        return $this->render($view, [
            'userDetail' => $userDetail
        ]);
    }

    /**
     * 文章
     * @return [type] [description]
     */
    public function actionPosts()
    {
        $uid = intval(Yii::$app->getRequest()->get('uid', Yii::$app->getUser()->getId()));

        if (!$uid) {
            return $this->redirect('/site/error');
        }

        $postView = new PostViews();
        $columns = ['postid', 'user_id', 'title', 'comments', 'views', 'status', 'created_at'];
        if ($uid === Yii::$app->getUser()->getId()) {
            $view = 'selfPosts';
            $user = Yii::$app->getUser()->getIdentity();
            $userDetail = User::getUserDetail($uid);
            $query = $postView->getMyPostViews()->select($columns);
        } else {
            $view = 'posts';
            $user = User::findOne($uid);
            $userDetail = User::getUserDetail($uid);
            $userDetail['isfollower'] = Followers::getFollowerStatus([$uid, $user->id]);
            $query = $postView->getPostViewsByUser($uid)->andWhere([
                'isopen' => PostViews::YES
            ])->select($columns);
        }

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

        return $this->render($view, [
            'userDetail' => $userDetail,
            'posts' => $posts,
            'pagination' => $pagination
        ]);
    }
    /**
     * 我的评论
     * @return [type] [description]
     */
    public function actionComments()
    {
        $user = Yii::$app->getUser()->getIdentity();
        $query = $user->getComments()
            ->select(['commentid', 'post_id', 'apps', 'opps', 'comment_at'])
            ->joinWith([
                'post' => function ($query) {
                    return $query->select(['title']);
                }
            ]);
        $pagination = new Pagination([
            'totalCount' => $query->count(),
            'pageSizeParam' => false,
        ]);

        $comments = $query->orderBy(['commentid' => SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $this->render('comments', [
            'uid' => $user->getId(),
            'userDetail' => User::getUserDetail($user->getId()),
            'comments' => $comments,
            'pagination' => $pagination
        ]);
    }

    /**
     * 我的私信
     * @return [type] [description]
     */
    public function actionMessages()
    {
        $user = Yii::$app->getUser()->getIdentity();
        $search = Yii::$app->getRequest()->get('s', 'to');
        if ($search == 'to') {
            $query = $user->getMessagesSendTo()
                ->with([
                    'sendfrom' => function ($query) {
                        return $query->select(['uid', 'head', 'sex', 'nickname']);
                    }
                ]);
        } else {
            $query = $user->getMessagesSendFrom()
                ->with([
                    'sendto' => function ($query) {
                        return $query->select(['uid', 'head', 'sex', 'nickname']);
                    }
                ]);
        }

        $pagination = new Pagination([
            'totalCount' => $query->count(),
            'pageSizeParam' => false
        ]);

        $messages = $query->orderBy(['id' => SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $this->render('messages', [
            'uid' => $user->getId(),
            'search' => $search,
            'userDetail' => User::getUserDetail($user->getId()),
            'messages' => $messages,
            'pagination' => $pagination
        ]);
    }

    /**
     * 关注页
     * @return [type] [description]
     */
    public function actionFriends()
    {
        $me = Yii::$app->getUser()->getId();
        $uid = intval(Yii::$app->getRequest()->get('uid', $me));

        if (!$uid) {
            return $this->redirect('/site/error');
        }
        $userDetail = User::getUserDetail($uid);

        if ($uid === $me) {
            $view = 'selfFriends';
            $user = Yii::$app->getUser()->getIdentity();
        } else {
            $view = 'friends';
            $user = User::findOne($uid);
            $userDetail['isfollower'] = Followers::getFollowerStatus([$uid, $me]);
        }
        $query = $user->getFriends()
            ->joinWith([
                'friend' => function ($query) {
                    return $query->select(['uid', 'nickname', 'head', 'sex', 'isauth']);
                }
            ]);
        $counts = $query->count();
        $pagination = new Pagination([
            'totalCount' => $counts,'pageSizeParam' => false,
            'pageSize' => Yii::$app->params['default.pageSize'],
        ]);

        $friends = $query->orderBy(['id' => SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $this->render($view, [
            'userDetail' => $userDetail,
            'friends' => $friends,
            'pagination' => $pagination
        ]);
    }

    /**
     * 粉丝页
     * @return [type] [description]
     */
    public function actionFollowers()
    {
        $me = Yii::$app->getUser()->getId();
        $uid = intval(Yii::$app->getRequest()->get('uid', $me));

        if (!$uid) {
            return $this->redirect('/site/error');
        }

        if ($uid === $me) {
            $view = 'selfFollowers';
            $user = Yii::$app->getUser()->getIdentity();
            $userDetail = User::getUserDetail($uid);
        } else {
            $view = 'followers';
            $user = User::findOne($uid);
            $userDetail = User::getUserDetail($uid);
            $userDetail['isfollower'] = Followers::getFollowerStatus([$uid, $me]);
        }
        $query = $user->getFollowers()
            ->joinWith([
                'follower' => function ($query) {
                    return $query->select(['uid', 'nickname', 'head', 'sex', 'isauth']);
                }
            ]);
        $counts = $query->count();
        $pagination = new Pagination([
            'totalCount' => $counts,'pageSizeParam' => false,
        ]);
        $pagination->setPageSize(1);
        $followers = $query
            ->orderBy(['id' => SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $this->render($view, [
            'userDetail' => $userDetail,
            'followers' => $followers,
            'pagination' => $pagination
        ]);
    }

    /**
     * 修改个人信息
     * @return [type] [description]
     */
    public function actionInfo()
    {
        $uid = Yii::$app->getUser()->getId();
        $model = new UserInfoForm();
        $userDetail = User::getUserDetailWithInfo($uid);
        $success = 0;
        if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
            $userDetail['userInfo']['birthday'] = $model->birthday;
            $userDetail['userInfo']['idcode'] = $model->idcode;
            $userDetail['userInfo']['realname'] = $model->realname;
            $userDetail['userInfo']['mobile'] = $model->mobile;
            $userDetail['sex'] = $model->sex;
            $userDetail['motto'] = $model->motto;
            $success = 1;
        }

        $userDetail['userInfo']['idcode'] = $userDetail['userInfo']['idcode'] ? $userDetail['userInfo']['idcode'] : '';
        return $this->render('editinfo', [
            'userDetail' => $userDetail,
            'model' => $model,
            'success' => $success
        ]);
    }

    /**
     * 第三方绑定列表
     * @return [type] [description]
     */
    public function actionLinkOauth()
    {
        $uid = Yii::$app->getUser()->getId();
        $userDetail = User::getUserDetailWithInfo($uid);
        return $this->render('linkOauth', [
            'userDetail' => $userDetail,
            'auth' => Auth::find()->where(['user_id' => $uid])->asArray()->all()
        ]);
    }

    /**
     * 解绑第三方帐号
     * @return [type] [description]
     */
    public function actionUnlinkOauth()
    {
        if (Yii::$app->getRequest()->getIsPost()) {
            $model = Auth::findOne(['id' => intval(Yii::$app->getRequest()->post('id')), 'user_id' => Yii::$app->getUser()->getId()]);
            if ($model && $model->delete()) {
                return json_encode(['ok' => 1]);
            }

            return json_encode(['ok' => 0]);
        }
    }

    /**
     * 邀请注册页
     * @return [type] [description]
     */
    public function actionInvite()
    {
        return $this->redirect(['/site/error']);
        $user = Yii::$app->getUser()->getIdentity();
        $uid = $user->getId();
        $userDetail = User::getUserDetail($uid);

        $params = [
            'uid' => $uid,
            'userDetail' => $userDetail,
        ];

        return $this->render('invite', $params);
    }
    /**
     * 发送邀请码
     * @return [type] [description]
     */
    public function actionInviteGuest()
    {
        if (Yii::$app->getRequest()->getIsPost()) {
            $email = Yii::$app->getRequest()->post('InviteForm');
            $model = new InviteForm();
            $model->user = Yii::$app->getUser()->getId();
            if ($model->load(Yii::$app->getRequest()->post()) && $model->invite()) {
                return json_encode($model->attributes);
            }
            $msg = [];
            if ($model->hasErrors()) {
                foreach ($model->errors as $key => $value) {
                    $msg[] = implode(',', $value);
                }
            }
            return json_encode(['error' => 1, 'msg' => implode(',', $msg)]);
        }
        return json_encode(['error' => 1, 'msg' => '非法提交！']);
    }

    /**
     * 修改个人头像
     * @return [type] [description]
     */
    public function actionAvatar()
    {
        $uid = Yii::$app->getUser()->getId();
        $userDetail = User::getUserDetail($uid);
        return $this->render('avatar', [
            'userDetail' => $userDetail
        ]);
    }


    /**
     * 修改收款二维码
     * @return [type] [description]
     */
    public function actionPayQrcode()
    {
        $uid = Yii::$app->getUser()->getId();
        $userDetail = User::getUserDetail($uid);
        return $this->render('pay-qrcode', [
            'userDetail' => $userDetail
        ]);
    }

    /**
     * 重新发送邀请码
     * @return [type] [description]
     */
    public function actionResendInvite()
    {
        if (Yii::$app->getRequest()->getIsPost()) {
            $model = new Invites();
            $model = $model->getSelfInvite(Yii::$app->getRequest()->post('id'));
            if ($model->resendEmail(Yii::$app->getUser()->identity->getNickname())) {
                return json_encode(['ok' => 1, 'send' => date('Y-m-d')]);
            }
        }
        return json_encode(['ok' => 0]);
    }
    /**
     * 获取邀请码列表
     * @return [type] [description]
     */
    public function actionGetInvitesList()
    {
        $this->layout = false;
        $query = Yii::$app->getUser()->getIdentity()->getInvites();
        $count = $query->count();

        $pagination = new Pagination([
            'totalCount' => $count,'pageSizeParam' => false,
        ]);
        $pagination->setPageSize(1);
        $invites = $query->orderBy(['id' => SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $this->render('_getInvitesList', [
            'invites' => $invites,
            'pagination' => $pagination
        ]);
    }
    /**
     * 加关注
     * @return [type] [description]
     */
    public function actionAddFollower()
    {
        if ($user = intval(Yii::$app->getRequest()->post('uid'))) {
            $follower = Yii::$app->getUser()->getId();
            //不能关注自己
            if ($user != $follower) {
                $model = Followers::findOne(['user_id' => $user, 'follower_id' => $follower]);
                if ($model) {
                    $model->status = Followers::YES;
                    $model->updated_at = date('Y-m-d H:i:s');
                } else {
                    $model = new Followers();
                    $model->user_id = $user;
                    $model->follower_id = $follower;
                    $model->status = Followers::YES;
                    $model->created_at = date('Y-m-d H:i:s');
                    $model->updated_at = $model->created_at;
                }
                if ($model->save(false)) {
                    User::increaseFriend($follower);
                    User::increaseFollower($user);
                    return json_encode(['ok' => 1]);
                }
            }
        }
        return json_encode(['ok' => 0]);
    }
    /**
     * 取消关注
     * @return [type] [description]
     */
    public function actionRemoveFollower()
    {
        if (Yii::$app->getRequest()->getIsPost()) {
            $follower = Yii::$app->getUser()->getId();
            $user = intval(Yii::$app->getRequest()->post('uid'));

            $model = Followers::findOne(['user_id' => $user, 'follower_id' => $follower]);
            $model->status = Followers::NO;
            $model->updated_at = date('Y-m-d H:i:s');

            if ($model->save()) {
                User::decreaseFriend($follower);
                User::decreaseFollower($user);
                return json_encode(['ok' => 1]);
            }
        }

        return json_encode(['ok' => 0]);
    }
    /**
     * 发纸条
     * @return [type] [description]
     */
    public function actionAjaxSendMessage()
    {
        $message = Yii::$app->getRequest()->post('message');
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        $myself = Yii::$app->getUser()->getId();
        if ($message['content'] && $message['user'] != $myself) {
            $model = new Messages();
            $model->sendfrom = $myself;
            $model->sendto = $message['user'];
            $model->content = Html::encode($message['content']);
            $model->send_at = date('Y-m-d H:i:s');
            if ($model->validate() && $model->save(false)) {
                return ['ok' => 1];
            }
        }

        return ['ok' => 0];
    }

    /**
     * 保存头像
     */
    public function actionAjaxUploadHead()
    {
        $ok = 0;
        if (Yii::$app->getRequest()->getIsPost()) {
            $base64Img = Yii::$app->getRequest()->post('img');
            if (preg_match('/^(data:image\/(jpg|png|gif|jpeg);base64,)/', $base64Img, $result)) {
                $uid = Yii::$app->getUser()->getId();
                $md5 = md5(time()+$uid);

                $basePath = Yii::getAlias(Yii::$app->params['image.basePath']);
                $headImgPath = Yii::$app->params['image.relativePath'] . $uid . '/' . Yii::$app->params['avatar.dirName'] . '/';
                $uploadFile = $basePath . $headImgPath . $md5 . '.' . $result[2];
                $thumbFile = $basePath . $headImgPath . $md5 . Yii::$app->params['avatar.defaultSuffix'];
                $avatarName = $headImgPath . $md5 . Yii::$app->params['avatar.defaultSuffix'];

                if (!is_dir($basePath . $headImgPath)) {
                    @mkdir($basePath . $headImgPath);
                }
                if (file_put_contents($uploadFile, base64_decode(str_replace($result[1], '', $base64Img)))) {
                    //按设定头像大小等比裁剪图片
                    \yii\imagine\Image::thumbnail($uploadFile, Yii::$app->params['avatar.maxCutValue'], Yii::$app->params['avatar.maxCutValue'], \Imagine\Image\ManipulatorInterface::THUMBNAIL_INSET)
                        ->save($thumbFile, ['quality' => 90]);

                    @unlink($uploadFile);//删除上传原图
                    if (is_file($thumbFile)) {
                        $user = Yii::$app->getUser()->getIdentity();
                        $user->head = Yii::$app->params['image.host'] . $avatarName;
                        $user->updated_at = date('Y-m-d H:i:s');
                        $user->save(false);
                        $ok = 1;
                    }
                }
            }
        }
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        return ['ok' => $ok];
        Yii::$app->end();
    }

    /**
     * 保存收款二维码图
     */
    public function actionAjaxUploadPayQrcode()
    {
        $ok = 0;
        if (Yii::$app->getRequest()->getIsPost()) {
            $base64Img = Yii::$app->getRequest()->post('img');
            if (preg_match('/^(data:image\/(jpg|png|gif|jpeg);base64,)/', $base64Img, $result)) {
                $uid = Yii::$app->getUser()->getId();

                $basePath = Yii::getAlias(Yii::$app->params['image.basePath']);
                $imgPath = Yii::$app->params['image.relativePath'] . $uid . '/' . Yii::$app->params['payQrcode.imageName'];
                $uploadFile = $basePath . $imgPath . '.' . $result[2];
                $thumbFile = $basePath . $imgPath . Yii::$app->params['payQrcode.defaultSuffix'];
                $payQrcodeName = $imgPath . Yii::$app->params['payQrcode.defaultSuffix'];

                if (!is_dir(dirname($uploadFile))) {
                    @mkdir(dirname($uploadFile));
                }
                if (file_put_contents($uploadFile, base64_decode(str_replace($result[1], '', $base64Img)))) {
                    //按设定大小等比裁剪图片
                    \yii\imagine\Image::thumbnail($uploadFile, Yii::$app->params['payQrcode.maxCutValueW'], Yii::$app->params['payQrcode.maxCutValueH'], \Imagine\Image\ManipulatorInterface::THUMBNAIL_INSET)
                        ->save($thumbFile, ['quality' => 90]);

                    @unlink($uploadFile);//删除上传原图
                    if (is_file($thumbFile)) {
                        $user = Yii::$app->getUser()->getIdentity();
                        $user->pay_qrcode = Yii::$app->params['image.host'] . $payQrcodeName . '?v=' . time();
                        $user->updated_at = date('Y-m-d H:i:s');
                        $user->save(false);
                        $ok = 1;
                    }
                }
            }
        }
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        return ['ok' => $ok];
        Yii::$app->end();
    }
}
