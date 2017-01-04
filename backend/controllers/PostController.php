<?php

namespace backend\controllers;

use Yii;
use backend\controllers\MainController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\PostCategoryForm;
use backend\models\PostsSearch;
use backend\models\PostCategorySearch;
use common\models\Posts;
use common\models\Terms;

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
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all TermCates models.
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

    public function actionUpdateCategory()
    {
        $id = intval(Yii::$app->getRequest()->get('id'));
        if (!$id) {
            return $this->redirect(['category']);
        }
        $categoryForm = new PostCategoryForm();
        if ($categoryForm->load(Yii::$app->getRequest()->post()) && $categoryForm->update()) {
            return $this->redirect(['category']);
        } else {
            $model = new Terms();
            $model = $model->findOne($id);
            return $this->render('updateCategory', [
                'model' => $model,
            ]);
        }
    }

    /**
     * 查找文章
     */
    public function actionAjaxSearchPosts()
    {
        $word = trim(Yii::$app->getRequest()->post('s'));

        if ($word) {
            $post = new Posts();
            $post = $post->find()->select('postid, title')->where(['like', 'title', $word])->andWhere(['and', ['isdel' => Posts::NO]])->asArray()->all();
            return json_encode($post);
        }
        return json_encode([]);
    }
}
