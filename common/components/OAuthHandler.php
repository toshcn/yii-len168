<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */


namespace common\components;

use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;
use common\models\Auth;
use common\models\User;

/**
 * AuthHandler handles successful authentication via Yii auth component
 */
class OAuthHandler
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function handle()
    {
        Yii::$app->getSession()->set('AUTH_CLIENT_USERINFO', null);
        $attributes = $this->client->getUserAttributes();
        $email = ArrayHelper::getValue($attributes, 'email');
        $id = ArrayHelper::getValue($attributes, 'id');
        $nickname = ArrayHelper::getValue($attributes, 'login');
        /* @var Auth $auth */
        $auth = Auth::find()->where(['source' => $this->client->getId(), 'source_id' => $id])->one();


        Yii::$app->getSession()->set('loginOS', $this->client->getTitle());
        Yii::$app->getSession()->set('loginBrowser', [$this->client->getTitle(), '']);
        if (Yii::$app->getUser()->isGuest) {
            if ($auth) { // login
                /* @var User $user */
                $user = $auth->user;
                Yii::$app->user->login($user);
            } else {
                if ($email !== null && User::find()->where(['email' => $email])->exists()) {
                    Yii::$app->getSession()->setFlash('AUTH_CLIENT_ERROR', [
                        Yii::t('common/sentence', "User with the same email as in {client} account already exists but isn't linked to it. Login using email first to link it.", ['client' => $this->client->getTitle()]),
                    ]);
                } else { // signup
                    Yii::$app->getSession()->set('AUTH_CLIENT_USERINFO', [
                        'username' => $nickname,
                        'email' => $email,
                        'source' => $this->client->getId(),
                        'avatar' => $attributes['avatar_url'],
                        'source_id' => $id,
                    ]);
                }
            }
        } else { // user already logged in
            if (!$auth) { // add auth provider
                $auth = new Auth([
                    'user_id' => Yii::$app->user->id,
                    'source' => $this->client->getId(),
                    'source_id' => (string)$attributes['id'],
                    'nickname' => $nickname,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                if ($auth->save()) {
                    Yii::$app->getSession()->setFlash('AUTH_CLIENT_LINK_OAUTH', 1);
                } else {
                    Yii::$app->getSession()->setFlash('AUTH_CLIENT_ERROR', [
                        Yii::t('common/sentence', 'Unable to link {client} account. There is something wrong for DB.', ['client' => $this->client->getTitle()]),
                    ]);
                }
            } else { // there's existing auth
                Yii::$app->getSession()->setFlash('AUTH_CLIENT_ERROR', [
                    Yii::t('common/sentence', 'Unable to link {client} account. There is another user using it.', ['client' => $this->client->getTitle()]),
                ]);
            }
        }
    }

    /**
     * @param User $user
     */
    private function updateUserInfo(User $user)
    {
        $attributes = $this->client->getUserAttributes();
        $github = ArrayHelper::getValue($attributes, 'login');
        if ($user->github === null && $github) {
            $user->github = $github;
            $user->save();
        }
    }
}
