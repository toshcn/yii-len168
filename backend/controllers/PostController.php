<?php

namespace backend\controllers;

use Yii;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\PostCategoryForm;
use backend\models\PostsSearch;
use backend\models\PostCategorySearch;
use common\models\Posts;
use common\models\Terms;
use backend\controllers\MainController;

/**
 * PostController implements the CRUD actions for TermCates model.
 * 文章 控制器
 *
 * @author Toshcn <toshcn@foxmail.com>
 * @version 0.1.0
 */
class PostController extends MainController
{
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
     * 文章列表页
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PostsSearch();
        $dataProvider = $searchModel->search(Yii::$app->getRequest()->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 文章分类
     * @return [type] [description]
     */
    public function actionCategory()
    {
        $searchModel = new PostCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->getRequest()->queryParams);
        $categoryForm = new PostCategoryForm();
        if ($categoryForm->load(Yii::$app->getRequest()->post()) && $categoryForm->create()) {
            return $this->redirect(['category']);
        } else {
            return $this->render('category', [
                'categoryForm' => $categoryForm,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }
    /**
     * 更新分类
     * @return minx
     */
    public function actionUpdateCategory($id)
    {
        $categoryForm = new PostCategoryForm();
        if ($categoryForm->load(Yii::$app->getRequest()->post(), 'Terms') && $categoryForm->update()) {
            return $this->redirect(['category']);
        } else {
            //var_dump($categoryForm->errors);die;
            $model = Terms::findOne($id);
            return $this->render('updateCategory', [
                'model' => $model,
            ]);
        }
    }

    /**
     * 删除分类
     * @return minx
     */
    public function actionDeleteCategory($id)
    {
        if ($id) {
            Terms::deleteCategory($id);
        }
        return $this->redirect(['category']);
    }

    /**
     * 查找文章
     */
    public function actionAjaxSearchPosts()
    {
        $word = trim(Yii::$app->getRequest()->post('s'));
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        if ($word) {
            $post = new Posts();
            $post = $post->find()->select('postid, title')->where(['like', 'title', $word])->andWhere(['and', 'status', Posts::STATUS_ONLINE])->asArray()->all();
            return $post;
        }
        return [];
    }

    /**
     * 置顶文章
     * @return json
     */
    public function actionAjaxStick()
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        $stick = (int) Yii::$app->getRequest()->post('stick', 0);
        $id = (array) Yii::$app->getRequest()->post('id', []);
        if ($id) {
            return ['ok' => Posts::stick($stick, $id)];
        }

        return ['ok' => false];
    }

    /**
     * 推荐文章
     * @return json
     */
    public function actionAjaxNice()
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        $nice = (int) Yii::$app->getRequest()->post('nice', 0);
        $id = (array) Yii::$app->getRequest()->post('id', []);
        if ($id) {
            return ['ok' => Posts::nice($nice, $id)];
        }

        return ['ok' => false];
    }

    /**
     * 锁定文章
     * @return json
     */
    public function actionAjaxLock()
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        $lock = (int) Yii::$app->getRequest()->post('lock', 0);
        $id = Yii::$app->getRequest()->post('id', []);
        if ($id) {
            return ['ok' => Posts::lock($lock, $id)];
        }

        return ['ok' => false];
    }
}
