<?php

namespace backend\controllers;

use Yii;
use common\models\Links;
use backend\models\LinkSearch;
use backend\controllers\MainController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\LinkForm;
use backend\models\LinkCategoryForm;
use backend\models\LinkCategorySearch;

/**
 * LinkController implements the CRUD actions for Links model.
 */
class LinkController extends MainController
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
     * Lists all Links models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LinkSearch();
        $linkForm = new LinkForm();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if ($linkForm->load(Yii::$app->getRequest()->post()) && $linkForm->create()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('index', [
                'route' => $this->route,
                'linkForm' => $linkForm,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }
    public function actionCategory()
    {
        $searchModel = new LinkCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->getRequest()->queryParams);
        $categoryForm = new LinkCategoryForm();
        if ($categoryForm->load(Yii::$app->getRequest()->post()) && $categoryForm->create()) {
            return $this->redirect(['category']);
        } else {
            return $this->render('category',[
                'route' => $this->route,
                'categoryForm' => $categoryForm,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Displays a single Links model.
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
     * Creates a new Links model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Links();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->linkid]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Links model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->linkid]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Links model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Links model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Links the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Links::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 查找链接
     */
    public function actionAjaxSearchLinks()
    {
        $word = trim(Yii::$app->getRequest()->post('s'));

        if ($word) {
            $link = new Links();
            $link = $link->find()->select('linkid, link_title')->where(['like', 'link_title', $word])->asArray()->all();
            return json_encode($link);
        }
        return json_encode([]);
    }

}
