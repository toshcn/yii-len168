<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license
 */

namespace frontend\modules\ucenter\controllers;

use Yii;
use yii\web\UploadedFile;
use yii\data\Pagination;
use yii\imagine\Image;
use common\models\Terms;
use common\models\Posts;
use common\models\User;
use common\models\PostForm;
use common\models\ImageForm;
use common\models\Images;
use common\models\ImageManager;
use frontend\modules\ucenter\controllers\CommonController;

/**
 * frontend 会员模块 文章控制器
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */
class PostController extends CommonController
{

    /**
     * 发表文章
     */
    public function actionCreate()
    {
        $model = new PostForm();
        if (Yii::$app->getRequest()->getIsPost()) {
            if ($model->load(Yii::$app->getRequest()->post()) && $model->createPost()) {
                if ($model->postid) {
                    $tags = $model->isdraft ? Posts::findOne($model->postid)->getTags()->asArray()->all() : [];
                    return json_encode([
                        'ok' => 1,
                        'id' => (int) $model->postid,
                        'tags' => $tags,
                        't' => date('Y-m-d H:i:s')
                    ]);
                }
            }
            $errors = [];
            foreach ($model->errors as $key => $error) {
                $errors[] = implode(',', $error);
            }
            return json_encode([
                'ok' => 0,
                'id' => (int) $model->postid,
                'error' => implode(', ', $errors),
                't' => date('Y-m-d H:i:s')
            ]);
        }
        $post = (new Posts)->attributes;
        $post['author'] = Yii::$app->getUser()->getIdentity()->nickname;
        return $this->render('create', [
            'postid' => 0,
            'post' => $post,
            'imageModel' => new ImageForm(),
            'categorys' => Terms::getTermChildrens(0, Terms::CATEGORY_CATE),
            'postTags' => [],
            'hotTags' => Terms::getHotTags(10),
            'parentPost' => '',
        ]);
    }
    /**
     * 编辑文章
     */
    public function actionUpdate()
    {
        if (Yii::$app->getRequest()->getIsPost()) {
            $model = new PostForm();
            $model->load(Yii::$app->getRequest()->post());
            if (!Yii::$app->user->can('updatePost', ['post' => Posts::findOne($model->postid)])) {
                return json_encode(['ok' => 0, 'error' => '你不是此文章作者！', 't' => date('Y-m-d H:i:s')]);
            }

            if ($model->updatePost()) {
                if (!$model->hasErrors()) {
                    $tags = $model->isdraft ? Posts::findOne($model->postid)->getTags()->asArray()->all() : [];

                    return json_encode([
                        'ok' => 1,
                        'id' => $model->postid,
                        'tags' => $tags,
                        't' => date('Y-m-d H:i:s')
                    ]);
                }
            }
            $errors = [];
            foreach ($model->errors as $key => $error) {
                $errors[] = implode(',', $error);
            }
            return json_encode([
                'ok' => 0,
                'id' => (int) $model->postid,
                'error' => implode(', ', $errors),
                't' => date('Y-m-d H:i:s')
            ]);
        }

        $id = intval(Yii::$app->getRequest()->get('id'));
        $post = Posts::findOne($id);

        if (!Yii::$app->user->can('updatePost', ['post' => $post])) {
            throw new \yii\web\HttpException(401, '你不是此文章作者！');
        }

        $term = new Terms();
        $recode = $post->attributes;
        if ($recode['image'] && $recode['image_suffix']) {
            $recode['image'] = $recode['image'] . '_326x195' . $recode['image_suffix'];
        }

        return $this->render('create', [
            'imageModel' => new ImageForm(),
            'postid' => $id,
            'categorys' => $term->getTermChildrens(0, Terms::CATEGORY_CATE),
            'post' => $recode,
            'postTags' => $id ? $post->getTags()->asArray()->all() : [],
            'hotTags' => Terms::getHotTags(10),
            'parentPost' => $post->parent ? $post->getParent()->select('postid, title')->asArray()->one() : [],
        ]);
    }

    /**
     * 获取子分类
     * @return array|null serialize
     */
    public function actionAjaxCateChildrens()
    {
        $parent = intval(Yii::$app->getRequest()->get('id'));
        if ($parent) {
            $term = new Terms();
            return json_encode($term->find()->where(['parent' => intval($parent), 'catetype' => Terms::CATEGORY_CATE])->asArray()->all());
        }
        return json_encode([]);
    }

    /**
     * 查找文章
     */
    public function actionAjaxSearchPosts()
    {
        $word = trim(Yii::$app->getRequest()->get('s'));
        $pid = intval(Yii::$app->getRequest()->get('pid'));
        if ($word) {
            $user = User::findOne(Yii::$app->user->id);
            $post = $user->getSearchPosts()->select('postid, title')->where(['like', 'title', $word])->andWhere(['and', ['status' => Posts::STATUS_ONLINE], ['!=', 'postid', $pid]])->asArray()->all();
            return json_encode($post);
        }
        return json_encode([]);
    }

    /**
     * 上传文章主图
     */
    public function actionUploadMainImage()
    {
        $model = new ImageForm();
        if (Yii::$app->getRequest()->getIsPost()) {
            $model->image = UploadedFile::getInstance($model, 'image');
            if ($model->image && $model->validate()) {
                $model->isCreateThumbnail = true;
                $image = $model->saveImage();
                if ($image) {
                    return json_encode([
                        'success'      => 1,
                        'id'           => $image['id'],
                        'title'        => $image['img_title'],
                        'name'         => $image['img_name'],
                        'path'         => $image['img_path'],
                        'suffix'       => $image['img_suffix'],
                        'width'        => $image['img_width'],
                        'height'       => $image['img_height'],
                        'size'         => $image['img_size'],
                        'thumb_path'   => $image['thumb_path'],
                        'thumb_suffix' => $image['thumb_suffix'],
                        'original'     => $image['img_original'],
                        'created_at'   => $image['created_at'],
                    ]);
                }
            }
            return json_encode(['error' => implode(',', $model->errors['image'])]);
        }
        return json_encode(['error' => '上传失败']);
    }

    /**
     * 上传图片
     * @return json
     */
    public function actionUploadImage()
    {
        if (Yii::$app->getRequest()->getIsPost()) {
            $model = new ImageForm();
            $model->postid = Yii::$app->getRequest()->post('pid');
            $model->image = UploadedFile::getInstance($model, 'image');
            if ($model->image && $model->validate()) {
                if ($image = $model->saveImage()) {
                    return json_encode([
                        'success'      => 1,
                        'id'           => $image['id'],
                        'title'        => $image['img_title'],
                        'name'         => $image['img_name'],
                        'path'         => $image['img_path'],
                        'suffix'       => $image['img_suffix'],
                        'width'        => $image['img_width'],
                        'height'       => $image['img_height'],
                        'size'         => $image['img_size'],
                        'thumb_path'   => $image['thumb_path'],
                        'thumb_suffix' => $image['thumb_suffix'],
                        'original'     => $image['img_original'],
                        'created_at'   => $image['created_at'],
                    ]);
                }
            }

            return json_encode(['error' => implode(',', $model->errors['image'])]);
        }

        return json_encode(['error' => '上传失败']);
    }

    /**
     * 查找图片
     * @return [type] [description]
     */
    public function actionSearchImages()
    {
        $cate = intval(Yii::$app->getRequest()->get('cate'));
        $date = Yii::$app->getRequest()->get('date');
        $p = Yii::$app->getRequest()->get('page', 1);
        $where = 'user_id=:user';
        $params[':user'] = Yii::$app->user->getId();
        if (in_array($cate, [0, 1])) {
            $where .= ' AND img_cate=:cate';
            $params[':cate'] = $cate;
        }
        if ($date != -1) {
            switch ($date) {
                case '3':
                    $where .= ' AND created_at >= :date';
                    $params[':date'] = date('Y-m-d', strtotime('-3 month'));
                    break;
                case '3-6':
                    $where .= ' AND created_at >= :start AND created_at <= :end';
                    $params[':start'] = date('Y-m-d', strtotime('-6 month'));
                    $params[':end'] = date('Y-m-d', strtotime('-3 month'));
                    break;
                case '6-12':
                    $where .= ' AND created_at >= :start AND created_at <= :end';
                    $params[':start'] = date('Y-m-d', strtotime('-1 year'));
                    $params[':end'] = date('Y-m-d', strtotime('-6 month'));
                    break;
                case '>12':
                    $where .= ' AND created_at <= :start';
                    $params[':start'] = date('Y-m-d', strtotime('-1 year'));
                    break;
                default:
                    break;
            }
        }
        $query = Images::find()->select('id, img_size, img_title, img_name, img_path, img_suffix, img_width, img_height, img_version, thumb_path, thumb_suffix, img_original, created_at')->where($where)->addParams($params);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pages->setPageSize(Yii::$app->params['image.pageSize']);
        $images = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy(['id' => SORT_DESC])
            ->asArray()
            ->all();
        $pageCount = $pages->getPageCount();
        $next = 0;
        if ($pageCount) {
            $next = ($p == $pageCount) ? 0 : $p + 1;
        }

        return json_encode(['images' => $images, 'next' => $next, 'cate' => $cate, 'date' => $date]);
    }

    /**
     * 修改编辑图片
     * @return json [description]
     */
    public function actionEditImage()
    {
        $uid = Yii::$app->user->getId();
        $imgId = Yii::$app->getRequest()->post('id');
        $image = Images::find()->where(['and', ['id' => $imgId, 'user_id' => $uid]])->one();
        if ($image) {
            $cropper = Yii::$app->getRequest()->post('image');
            $image->img_title = trim(Yii::$app->getRequest()->post('title'));

            $imageBasePath = Yii::getAlias(Yii::$app->params['image.basePath']);
            $imageManager = new ImageManager();
            $imageManager->originalImageFlag = $image->img_original;

            $imageManager->setImageFile($imageBasePath . $image->img_path . $image->img_name . $imageManager->originalImageFlag . $image->img_suffix);

            $imageManager->imagePath = $imageBasePath . $image->img_path;
            $imageManager->thumbPath = $imageBasePath . $image->thumb_path;
            $imageManager->setExtension($image->img_suffix);
            $imageManager->imageName = $image->img_name;

            $cropper['width'] = intval($cropper['width']);
            $cropper['height'] = intval($cropper['height']);
            $cropper['x'] = $cropper['x'] >= 0 ? intval($cropper['x']) : 0;
            $cropper['y'] = $cropper['y'] >= 0 ? intval($cropper['y']) : 0;
            if ($imageManager->edit($cropper['width'], $cropper['height'], [$cropper['x'], $cropper['y']])) {
                $image->img_version += 1;
                $image->save();

                return json_encode(['ok' => 1, 'version' => $image->img_version]);
            }
        }

        return json_encode(['ok' => 0, 'error' => '操作失败']);
    }
}
