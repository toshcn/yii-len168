<?php
/**
 * @link http://www.shuimugua.com/
 * @copyright Copyright (c) 2015 shuimugua.com
 * @license
 */

namespace common\models;

use yii;
use yii\base\Object;
use yii\imagine\Image;

/**
 *
 */
class ImageManager extends Object
{
    /** @var string 图片名称 */
    public $imageName = '';
    /** @var string 原图标识 */
    public $originalImageFlag = '_original';
    /** @var integer 图片最大宽度 像素值 */
    public $imageMaxWidth = 1200;
    /** @var string 图片保存目录 */
    public $imagePath = '';
    /** @var boolean 是否生成缩略图 */
    public $isCreateThumbnail = false;
    /** @var array 图片缩略图规格，[长，宽] 比例5:3 */
    public $thumbnail = [['w' => 326, 'h' => 195], ['w' => 500, 'h' => 300]];
    /**
     * @var string 缩略图生成方式 [@see \Imagine\Image\ManipulatorInterface]
     * 等比例：THUMBNAIL_INSET 填充:THUMBNAIL_OUTBOUND
     */
    public $thumbnailMode = \Imagine\Image\ManipulatorInterface::THUMBNAIL_INSET;
    /** @var string 缩略图的后缀名: .jpg .png */
    public $thumbnailType = '.jpg';
    /** @var integer 压缩图像质量0-100 */
    public $quality = 90;
    /** @var string 图片缩略图保存目录 */
    public $thumbPath = '';
    /** @var boolean 是否添加水印 */
    public $makeWater = false;
    public $waterType = '';
    public $waterImage = '';
    public $waterImageSize = [];
    public $waterLocation = 'rightBottom';
    public $waterPadding = [0, 0];

    public $waterText = '';
    public $fontFile = '';
    /**
     * @var array 文字水印字体配置数组
     * ```php
     * [
     *      'size' => 12,
     *      'color' => 'fff',
     *      'angle' => 0
     *  ];
     * ```php
     *
     */
    public $fontOptions = [];

    /** @var string 操作的图片真实路径 */
    private $imageFile;
    private $width;
    private $height;
    private $md5;
    private $sha1;
    /** @var string 图片的扩展名 */
    private $extension = '.jpg';

    public function init()
    {
        if (empty($this->imageName)) {
            $this->imageName = md5(\Yii::$app->user->getId() + time());
        }

        $this->quality        = Yii::$app->params['image.quality'];
        $this->makeWater      = Yii::$app->params['image.makeWater'];
        $this->waterType      = Yii::$app->params['image.waterConfig']['type'];
        $this->waterLocation  = Yii::$app->params['image.waterConfig']['location'];
        $this->waterPadding   = Yii::$app->params['image.waterConfig']['padding'];
        $this->waterImage     = Yii::$app->params['image.waterConfig']['image'];
        $this->waterImageSize = Yii::$app->params['image.waterConfig']['size'];
        $this->waterText      = Yii::$app->params['image.waterConfig']['text'];
        $this->fontFile       = Yii::$app->params['image.waterConfig']['fontFile'];
        $this->fontOptions    = Yii::$app->params['image.waterConfig']['fontOptions'];
        if (!in_array($this->waterType, ['water', 'text'])) {
            $this->makeWater = false;
        }
    }
    /**
     * [getImageFile description]
     * @return [type] [description]
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }
    /**
     * [setImageFile description]
     * @return [type] [description]
     */
    public function setImageFile($file)
    {
        $this->imageFile = $file;
    }
    public function getWidth()
    {
        return $this->width;
    }
    public function getHeight()
    {
        return $this->height;
    }
    /**
     * [getMd5 description]
     * @return [type] [description]
     */
    public function getMd5()
    {
        if ($this->md5) {
            return $this->md5;
        } else {
            return $this->md5 = md5_file($this->imageFile);
        }
    }

    public function getSha1()
    {
        if ($this->sha1) {
            return $this->sha1;
        } else {
            return $this->sha1 = sha1_file($this->imageFile);
        }
    }
    /**
     * [getExtension description]
     * @return [type] [description]
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * [setExtension description]
     * @return [type] [description]
     */
    public function setExtension($extension)
    {
        if ($extension) {
            $this->extension = $extension == '.png' ? '.jpg' : $extension;
        }
    }

    public function save()
    {
        if (is_file($this->imageFile) && $this->imagePath) {
            $imageSize = getimagesize($this->imageFile);
            $width = $imageSize[0];//图片宽度像素
            $height = $imageSize[1];//图片高度像素
            if (!is_dir($this->imagePath)) {
                @mkdir($this->imagePath, 0764, true);
            }
            //限制图片最大宽度
            if ($imageSize[0] > $this->imageMaxWidth) {
                $width = $this->imageMaxWidth;
                $height = ceil($this->imageMaxWidth / $imageSize[0] * $height);
            }
            $this->width = $width;
            $this->height = $height;
            /** @var string 原图文件 */
            $originalFile = $this->imagePath . $this->imageName . $this->originalImageFlag . $this->extension;
            //保存原图
            Image::thumbnail($this->imageFile, $width, $height, $this->thumbnailMode)->save($originalFile, ['quality' => $this->quality]);

            if (is_file($originalFile)) {
                //保存图片成功后, 生成缩略图
                if ($this->isCreateThumbnail === true && $this->thumbPath) {
                    if (!is_dir($this->thumbPath)) {
                        @mkdir($this->thumbPath, 0764, true);
                    }
                    foreach ($this->thumbnail as $key => $thumb) {
                        $savePath = $this->thumbPath . $this->imageName . '_' . $thumb['w'] .'x' . $thumb['h'] . $this->thumbnailType;
                        Image::thumbnail($originalFile, $thumb['w'], $thumb['h'], $this->thumbnailMode)->save($savePath, ['quality' => $this->quality]);
                    }
                }
                /** @var string 展示图片 */
                $privewImage = $this->imagePath . $this->imageName . $this->extension;
                //添加水印
                if ($this->makeWater === true) {
                    if ($this->waterType == 'water') {
                        $start = $this->getWaterStart($width, $height);
                        $this->makeWater($originalFile, $privewImage, $this->waterImage, $start);
                    } else if ($this->waterType == 'text') {
                        $this->makeText($originalFile, $privewImage, $text, $fontFile, $fontOptions);
                    }
                } else {
                    copy($originalFile, $privewImage);
                }
                return true;
            }
        }
        return false;
    }

    public function edit($cropWidth = 0, $cropHeight = 0, array $start = [0, 0])
    {
        if (is_file($this->imageFile) && $this->imagePath) {
            if (!is_dir($this->imagePath)) {
                @mkdir($this->imagePath, 0764, true);
            }
            //限制图片最大宽度
            if ($cropWidth > $this->imageMaxWidth || $cropWidth == 0 || $cropHeight == 0) {
                return false;
            }
            $this->width = $width = ceil($cropWidth);
            $this->height = $height = ceil($cropHeight);
            /** @var string 原图 */
            $originalFile = $this->imageFile;
            /** @var string 展示图 */
            $privewImage = $this->imagePath . $this->imageName . $this->extension;
            //修改展示图
            Image::crop($originalFile, $width, $height, $start)->save($privewImage, ['quality' => $this->quality]);

            if (is_file($privewImage)) {
                //保存图片成功后, 生成缩略图
                if ($this->thumbPath) {
                    foreach ($this->thumbnail as $key => $thumb) {
                        $savePath = $this->thumbPath . $this->imageName . '_' . $thumb['w'] .'x' . $thumb['h'] . $this->thumbnailType;
                        if (is_file($savePath)) {
                            if (!is_dir($this->thumbPath)) {
                                @mkdir($this->thumbPath, 0764, true);
                            }
                            Image::thumbnail($privewImage, $thumb['w'], $thumb['h'], $this->thumbnailMode)->save($savePath, ['quality' => $this->quality]);
                        }
                    }
                }
                //添加水印
                if ($this->makeWater === true) {
                    if ($this->waterType == 'water') {
                        $start = $this->getWaterStart($width, $height);
                        $this->makeWater($privewImage, $privewImage, $this->waterImage, $start);
                    } else if ($this->waterType == 'text') {
                        $this->makeText($privewImage, $privewImage, $text, $fontFile, $fontOptions);
                    }
                }
                return true;
            }
        }
        return false;
    }

    public function createThumbnail(string $image, int $width, int $height, $mode = '', $savePath = '', $quality = 0)
    {
        $mode = $mode ? $mode : $this->thumbnailMode;
        $quality = $quality ? $quality : $this->quality;
        Image::thumbnail($image, $width, $height, $mode)->save($savePath, ['quality' => $quality]);
        if (is_file($savePath)) {
            return $savePath;
        }
        return false;
    }

    public function makeWater($image, $savePath, $waterImage, array $start, $quality = 0)
    {
        if (empty($start)) {
            $start = [0, 0];
        }
        $quality = $quality ? $quality : $this->quality;
        //图片水印
        Image::watermark($image, $waterImage, $start)->save($savePath, ['quality' => $quality]);
        if (is_file($savePath)) {
            return true;
        }
        return false;
    }

    public function makeText($image, $savePath, $text, $fontFile, $fontOptions, $quality = 0)
    {
        $start = $this->getTextStart($text, $fontOptions['size']);
        $quality = $quality ? $quality : $this->quality;
        //文字水印
        Image::text($image, $text, $fontFile, $start, $fontOptions)->save($savePath, ['quality' => $quality]);
        if (is_file($savePath)) {
            return true;
        }
        return false;
    }

    private function getWaterStart($width = 0, $height = 0)
    {
        $width = $width ? $width : $this->width;
        $height = $height ? $height : $this->height;
        if ($this->waterImageSize) {
            $waterInfo = $this->waterImageSize;
        } else {
            $waterSize = getimagesize($this->waterImage);
            $waterInfo[0] = $waterSize[0];
            $waterInfo[1] = $waterSize[1];
        }
        $padding[0] = abs($this->waterPadding[0]);
        $padding[1] = abs($this->waterPadding[1]);
        switch ($this->waterLocation) {
            case 'center':
                $start[0] = round($width / 2 - $waterInfo[0] / 2 - $padding[0]);
                $start[1] = round($height / 2 - $waterInfo[1] / 2 - $padding[1]);
                break;
            case 'leftTop':
                $start[0] = $padding[0];
                $start[1] = $padding[1];
                break;
            case 'rightTop':
                $start[0] = $width - $waterInfo[0] - $padding[0];
                $start[1] = $padding[1];
                break;
            case 'leftBottom':
                $start[0] = $padding[0];
                $start[1] = $height - $waterInfo[1] - $padding[1];
                break;
            case 'rightBottom':
                $start[0] = $width - $waterInfo[0] - $padding[0];
                $start[1] = $height - $waterInfo[1] - $padding[1];
                break;
            default:
                $start = $padding;
                break;
        }
        return $start;
    }

    private function getTextStart($text, $size = 12)
    {
        $waterInfo[0] = intval(mb_strlen($text) * $size);
        $waterInfo[1] = $size;

        $padding[0] = abs($this->waterPadding[0]);
        $padding[1] = abs($this->waterPadding[1]);
        switch ($this->waterLocation) {
            case 'center':
                $start[0] = round($width / 2 - $waterInfo[0] / 2 - $padding[0]);
                $start[1] = round($height / 2 - $waterInfo[1] / 2 - $padding[1]);
                break;
            case 'leftTop':
                $start[0] = $padding[0];
                $start[1] = $padding[1];
                break;
            case 'rightTop':
                $start[0] = $width - $waterInfo[0] - $padding[0];
                $start[1] = $padding[1];
                break;
            case 'leftBottom':
                $start[0] = $padding[0];
                $start[1] = $height - $waterInfo[1] - $padding[1];
                break;
            case 'rightBottom':
                $start[0] = $width - $waterInfo[0] - $padding[0];
                $start[1] = $height - $waterInfo[1] - $padding[1];
                break;
            default:
                $start = $padding;
                break;
        }
        return $start;
    }

    public function rollback()
    {
        @unlink($this->imagePath . $this->imageName . $this->originalImageFlag . $this->extension);
        if ($this->isCreateThumbnail === true) {
            foreach ($this->thumbnail as $key => $thumb) {
                $savePath = $this->thumbPath . $this->imageName . '_' . $thumb['w'] .'x' . $thumb['h'] . $this->thumbnailType;
                @unlink($savePath);
            }
        }
    }
}
