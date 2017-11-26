<?php
/**
 * @link http://www.shuimugua.com/
 * @copyright Copyright (c) 2015 shuimugua.com
 * @license
 */

namespace common\models;

use Yii;
use yii\imagine\Image;
use yii\base\Model;
use yii\helpers\FileHelper;
use common\models\Images;
use common\models\ImageManager;
use yii\web\UploadedFile;

/**
 * 图片表单类
 */
class ImageForm extends Model
{
    /**
     * @var 允许上传的图片扩展名
     */
    const EXTENSIONS = 'jpg, png, gif, jpeg';
    const MAX_UPLOAD_SIZE = 2* 1024 * 1024 * 1024;
    const MIMETYPES = ['image/jpeg', 'image/png', 'image/gif'];

    /** @var integer 文章ID */
    public $postid = 0;
    /** @var boolean 是否生成缩略图 */
    public $isCreateThumbnail = false;

    /** @var object UploadedFile object */
    public $image;


    public function rules()
    {
        return [
            ['image', 'file', 'extensions' => self::EXTENSIONS, 'mimeTypes' => self::MIMETYPES, 'wrongMimeType' => '错误的图片文件', 'maxSize' => self::MAX_UPLOAD_SIZE, 'tooBig' => '上传的图片不能大于2M'],
        ];
    }

    /**
     * 保存上传的图片, 根据isCreateThumbnail是否生成缩略图
     * 图片保存的目录为 图片保存目录/会员ID/当前年月日/图片名.后缀名
     * 图片缩略图保存的目录为 图片缩略图保存目录/会员ID/当前年月日/图片名_宽x高.后缀名
     * @return [type] [description]
     */
    public function saveImage()
    {
        $model = new Images();
        $uid = Yii::$app->user->getId();
        $row = $model->find()->where(['user_id' => $uid])->andWhere(['img_md5' => md5_file($this->image->tempName)])->select('id')->asArray()->one();
        if ($row) {
            $this->addError('image', '请勿重复上传!');
            return false;
        }

        $dirRule = $uid . '/' . date('Y-m-d') . '/';
        $imageManager = new ImageManager();
        $imageBasePath = Yii::getAlias(Yii::$app->params['image.basePath']);

        $imageManager->setImageFile($this->image->tempName);
        $imageManager->setExtension('.'.$this->image->getExtension());

        $imageManager->imagePath = $imageBasePath . Yii::$app->params['image.relativePath'] . $dirRule;
        $imageManager->thumbPath = $imageBasePath . Yii::$app->params['thumb.relativePath'] . $dirRule;

        $imageManager->imageName = md5(time() + $uid + mt_rand(1, 99999));
        $imageManager->originalImageFlag = Yii::$app->params['image.originalImageFlag'];
        $imageManager->quality = Yii::$app->params['image.quality'];
        $imageManager->isCreateThumbnail = $this->isCreateThumbnail;
        $imageManager->makeWater = Yii::$app->params['image.makeWater'];
        $imageManager->waterType = Yii::$app->params['image.waterConfig']['type'];

        if ($imageManager->makeWater === true) {
            //添加水印
            $imageManager->waterLocation = Yii::$app->params['image.waterConfig']['location'];
            $imageManager->waterPadding = Yii::$app->params['image.waterConfig']['padding'];
            if ($imageManager->waterType == 'water') {
                $imageManager->waterImage = Yii::$app->params['image.waterConfig']['image'];
                $imageManager->waterImageSize = Yii::$app->params['image.waterConfig']['size'];
            } elseif ($imageManager->waterType == 'text') {
                $imageManager->text = Yii::$app->params['image.waterConfig']['text'];
                $imageManager->fontFile = Yii::$app->params['image.waterConfig']['fontFile'];
                $imageManager->fontOptions = Yii::$app->params['image.waterConfig']['fontOptions'];
            } else {
                $imageManager->makeWater = false;
            }
        }

        $imageManager->save();

        //保存到图片表
        $model->user_id = $uid;
        $model->img_name = $imageManager->imageName;
        $model->img_title = $uid . date('YmdHis');
        $model->img_cate = $this->postid ? 1 : 0;
        $model->img_path = Yii::$app->params['image.relativePath'] . $dirRule;
        $model->img_size = $this->image->size;
        $model->img_mime = $this->image->type;
        $model->img_suffix = $imageManager->extension;
        $model->img_width = $imageManager->width;
        $model->img_height = $imageManager->height;
        $model->img_md5 = $imageManager->md5;
        $model->img_sha1 = $imageManager->sha1;
        $model->img_version = 1;
        $model->img_original = Yii::$app->params['image.originalImageFlag'];
        $model->thumb_path = Yii::$app->params['thumb.relativePath'] . $dirRule;
        $model->thumb_suffix = Yii::$app->params['thumb.suffix'];
        $model->created_at = date('Y-m-d H:i:s');
        if ($model->save(false)) {
            return $model;
        }
        $imageManager->rollback();
        $this->addError('image', '上传失败!');
        return false;
    }
}
