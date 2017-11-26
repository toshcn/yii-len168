<?php

namespace backend\controllers;

use Yii;
use yii\web\Response;
use common\models\User;
use backend\models\UserSearch;
use backend\controllers\AuthController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends AuthController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * 更改状态
     * @return json
     */
    public function actionAjaxStatus()
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        $status = Yii::$app->getRequest()->post('status');
        $id = Yii::$app->getRequest()->post('id', []);
        if ($id && $status !== null) {
            return ['ok' => User::updateStatus($status, $id)];
        }

        return ['ok' => 0];
    }
    /**
     * 认证
     * @return json
     */
    public function actionAjaxAuth()
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        $auth = Yii::$app->getRequest()->post('auth');
        $id = Yii::$app->getRequest()->post('id', []);
        if ($id && $auth !== null) {
            return ['ok' => User::updateAuth($auth, $id)];
        }

        return ['ok' => 0];
    }



    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
