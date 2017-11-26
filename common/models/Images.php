<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%images}}".
 *
 * @property integer $id
 * @property integer $user
 * @property string $img_name
 * @property string $img_title
 * @property integer $img_cate
 * @property string $img_path
 * @property integer $img_size
 * @property string $img_mime
 * @property string $img_suffix
 * @property string $img_md5
 * @property string $img_sha1
 * @property string $img_version
 * @property string $created_at
 */
class Images extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%images}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'img_cate', 'img_size', 'img_width', 'img_height'], 'integer'],
            [['img_name', 'img_path', 'img_suffix', 'created_at','img_width', 'img_height' ], 'required'],
            [['img_name', 'img_title'], 'string', 'max' => 64],
            [['img_path', 'thumb_path'], 'string', 'max' => 128],
            ['img_mime', 'string', 'max' => 20],
            [['img_suffix', 'thumb_suffix', 'img_original'], 'string', 'max' => 10],
            ['img_md5', 'string', 'min' => 32, 'max' => 32],
            ['img_sha1', 'string', 'max' => 40, 'max' => 40],
            ['img_version', 'default', 'value' => 1],
            ['img_version', 'integer'],
            ['created_at', 'safe'],
        ];
    }


    /**
     * 继承父类实现并不想用一些敏感字段
     * @return [type] [description]
     */
    public function fields()
    {
        $fields = parent::fields();

        // 过滤掉一些字段
        unset($fields['img_md5'], $fields['img_sha1']);

        return $fields;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common/images', 'ID'),
            'user_id' => Yii::t('common/images', '会员帐号'),
            'img_name' => Yii::t('common/images', '图片名称'),
            'img_title' => Yii::t('common/images', '图片标题描述'),
            'img_cate' => Yii::t('common/images', '图片用途0无分类，1文章'),
            'img_path' => Yii::t('common/images', '图片保存路径'),
            'img_size' => Yii::t('common/images', '图像大小'),
            'img_mime' => Yii::t('common/images', '图像MIME'),
            'img_suffix' => Yii::t('common/images', '图像后缀名'),
            'img_width' => Yii::t('common/images', '图像宽度'),
            'img_height' => Yii::t('common/images', '图像高度'),
            'img_md5' => Yii::t('common/images', '图像的md5值'),
            'img_sha1' => Yii::t('common/images', '图像的sha1值'),
            'img_version' => Yii::t('common/images', '图像的版本'),
            'created_at' => Yii::t('common/images', '图像上传日期'),
        ];
    }

    public function findByMd5($md5)
    {
        return $this->find()->where(['img_md5' => $md5]);
    }

    public function findBySha1($sha1)
    {
        return $this->find()->where(['img_sha1' => $sha1]);
    }
}
