<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

namespace common\models;

use Yii;
use common\models\User;

/**
 * 文章活动记录
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */
class Posts extends \yii\db\ActiveRecord
{
    const COPYRIGHT_INDICATE_THE_SOURCE = 0;//版权 转载注明来源
    const COPYRIGHT_CONTACT_THE_AUTHOR = 1;//版权 转载联系作者
    const COPYRIGHT_FORBIDDEN_REPRINT = 2;//版权 严禁转载

    const SPEND_TYPE_POINT = 0;// 0积分
    const SPEND_TYPE_GOLD = 1;// 1金币
    const SPEND_TYPE_CRYSTAL = 2;// 2水晶

    const POST_TYPE_ORIGINAL = 1;//1原创
    const POST_TYPE_REPRINT = 2;//2转载
    const POST_TYPE_TRANSLATE = 3;//3翻译

    const STATUS_DELETED = -1;//文章状态已删除
    const STATUS_DRAFT = 0;//文章状态草稿
    const STATUS_ONLINE = 1;//文章状态发表

    const YES = 1;
    const NO = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%posts}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['postid', 'required', 'on' => 'update'],

            [['title', 'author', 'categorys', 'content', 'image', 'original_url', 'description'], 'trim'],
            [['title', 'author', 'content'], 'required'],
            ['title', 'string', 'max' => 60],
            ['author', 'string', 'max' => 15],
            ['image_suffix', 'string', 'max' => 20],

            [['iscomment', 'isopen'], 'default', 'value' => self::YES],
            [['content_len', 'parent', 'spend', 'islock', 'isstick', 'isnice', 'ispay', 'isforever', 'isdie'], 'default', 'value' => self::NO],

            [['content_len', 'spend', 'parent', 'status'], 'integer'],

            ['original_url', 'url'],
            ['original_url', 'string', 'max' => 255],
            [['image', 'description'], 'string', 'max' => 255],

            ['copyright', 'default', 'value' => self::COPYRIGHT_INDICATE_THE_SOURCE],
            ['copyright', 'in', 'range' => [self::COPYRIGHT_INDICATE_THE_SOURCE, self::COPYRIGHT_CONTACT_THE_AUTHOR, self::COPYRIGHT_FORBIDDEN_REPRINT]],

            ['paytype', 'in', 'range' => [self::SPEND_TYPE_POINT, self::SPEND_TYPE_CRYSTAL, self::SPEND_TYPE_GOLD]],

            ['posttype', 'default', 'value' => self::POST_TYPE_ORIGINAL],
            ['posttype', 'in', 'range' => [self::POST_TYPE_ORIGINAL, self::POST_TYPE_REPRINT, self::POST_TYPE_TRANSLATE]],

            [['islock', 'iscomment', 'isstick', 'isopen', 'ispay', 'isforever', 'isdie'], 'in', 'range' => [self::NO, self::YES]],

            ['user_id', 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'uid']],

            [['os', 'browser', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'postid'        => Yii::t('common/label', 'Post ID'),
            'user_id'       => Yii::t('common/label', 'User ID'),
            'title'         => Yii::t('common/label', 'Post Title'),
            'author'        => Yii::t('common/label', 'Author'),
            'image'         => Yii::t('common/label', 'Post Main Image'),
            'imageSuffix'   => Yii::t('common/label', 'Post Main Image Suffix'),
            'categorys'     => Yii::t('common/label', 'Categorys'),
            'content'       => Yii::t('common/label', 'Post Content'),
            'content_len'   => Yii::t('common/label', 'Post Content length'),
            'description'   => Yii::t('common/label', 'Post Description'),
            'original_url' => Yii::t('common/label', 'Original Link'),
            'copyright'     => Yii::t('common/label', 'Copyright'),
            'spend'         => Yii::t('common/label', 'Spend For Read'),
            'paytype'       => Yii::t('common/label', 'Spend Type'),
            'posttype'      => Yii::t('common/label', 'Post Type'),
            'parent'        => Yii::t('common/label', 'Parent Post'),
            'iscomment'     => Yii::t('common/label', 'Open Comment'),
            'isopen'        => Yii::t('common/label', 'Open Read'),
            'ispay'         => Yii::t('common/label', 'Pay'),
            'islock'        => Yii::t('common/label', 'Lock Post'),
            'isstick'       => Yii::t('common/label', 'Stick'),
            'isnice'        => Yii::t('common/label', 'Nice'),
            'isforever'     => Yii::t('common/label', 'Is Forever'),
            'isdie'         => Yii::t('common/label', 'Is Die'),
            'status'        => Yii::t('common/label', 'Post Status'),
            'os'            => Yii::t('common/label', 'Login OS'),
            'browser'       => Yii::t('common/label', 'Login Browser'),
            'created_at'    => Yii::t('common/label', 'Created At'),
            'updated_at'    => Yii::t('common/label', 'Updated At'),
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['user_id', 'title', 'author', 'image', 'image_suffix', 'categorys', 'content', 'content_len', 'description', 'original_url', 'copyright', 'spend', 'paytype', 'posttype', 'parent', 'islock', 'iscomment', 'isopen', 'ispay', 'status', 'os', 'browser', 'created_at', 'updated_at'];
        $scenarios['update'] = ['postid', 'user_id', 'title', 'author', 'image', 'image_suffix', 'categorys', 'content', 'content_len', 'description', 'original_url', 'copyright', 'spend', 'paytype', 'posttype', 'parent', 'islock', 'iscomment', 'isstick', 'isnice', 'isopen', 'ispay', 'isforever', 'isdie', 'status', 'updated_at'];
        return $scenarios;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comments::className(), ['post_id' => 'postid'])
            ->with([
                'user' => function ($query) {
                    return $query->select(['uid', 'nickname', 'sex', 'isauth', 'head']);
                }]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostAttributes()
    {
        return $this->hasOne(PostAttributes::className(), ['post_id' => 'postid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['uid' => 'user_id']);
    }

    /**
     * 首页文章
     * @return \yii\db\ActiveQuery
     */
    public function getHostPosts()
    {
        return $this->find()->where(['isstick' => 1]);
    }

    public function getPosts()
    {
        return $this->find()->where(['isstick' => 1]);
    }

    public function getPost($postid)
    {
        return $this->findOne($postid);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTermRelations()
    {
        return $this->hasMany(TermRelations::className(), ['objectid' => 'postid'])->where(['type' => TermRelations::OBJECT_TYPE_POST]);
    }

    /**
     * 获取文章绑定的全部标签
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Terms::className(), ['termid' => 'term'])->where(['catetype' => Terms::CATEGORY_TAG])->via('termRelations');
    }

    public function getParent()
    {
        return $this->find()->where(['postid' => $this->parent]);
    }

    public function getCreatedBy()
    {
        return $this->user_id;
    }

    public static function transformPostType($type)
    {
        switch ($type) {
            case Posts::POST_TYPE_ORIGINAL:
                $posttype  = '原创';
                break;
            case Posts::POST_TYPE_REPRINT:
                $posttype  = '转载';
                break;
            case Posts::POST_TYPE_TRANSLATE:
                $posttype  = '翻译';
                break;
            default:
                $posttype = '';
        }
        return $posttype;
    }

    public static function transformPostCopyright($copyright)
    {
        switch ($copyright) {
            case Posts::COPYRIGHT_INDICATE_THE_SOURCE:
                $copyright  = '转载请注明来源';
                break;
            case Posts::COPYRIGHT_CONTACT_THE_AUTHOR:
                $copyright  = '转载请联系作者';
                break;
            case Posts::COPYRIGHT_FORBIDDEN_REPRINT:
                $copyright  = '严禁转载';
                break;
            default:
                $copyright = '';
        }
        return $copyright;
    }

    /**
     * 文章状态
     * @param  integer $status 状态
     * @return string         转换后的名称
     */
    public static function transformPostStatus($status)
    {
        switch ($status) {
            case Posts::STATUS_ONLINE:
                $status  = '发表';
                break;
            case Posts::STATUS_DRAFT:
                $status  = '草稿';
                break;
            case Posts::STATUS_DELETED:
                $status  = '已删除';
                break;
            default:
                $status = '';
        }
        return $status;
    }

    /**
     * 状态map
     * @return array
     */
    public static function getStatusMap()
    {
        return [
            '' => '请选择',
            self::STATUS_DELETED => '已删除',
            self::STATUS_DRAFT => '草稿',
            self::STATUS_ONLINE => '已发表',
        ];
    }

    /**
     * boolean map yes or no
     * @return array
     */
    public static function getBooleanMap()
    {
        return [
            '' => '请选择',
            self::YES => '是',
            self::NO => '否',
        ];
    }

    /**
     * 最近文章
     * @param  string  $column [description]
     * @param  integer $limit  [description]
     * @return [type]          [description]
     */
    public function getPostRecent($column = '*', $limit = 10)
    {
        return $this->find()->select($column)->where(['status' => Posts::STATUS_ONLINE])->limit(intval($limit))->asArray()->all();
    }

    /**
     * 置顶文章
     * @param  integer $stick 置顶状态0,1
     * @param  array|integer $id    文章id
     * @return boolean
     */
    public static function stick($stick, $id)
    {
        if (!is_array($id)) {
            $id = [$id];
        }

        return (boolean) static::updateAll(
            ['isstick' => (int) $stick],
            ['postid' => $id]
        );
    }

    /**
     * 推荐文章
     * @param  integer $nice 推荐状态0,1
     * @param  array|integer $id    文章id
     * @return boolean
     */
    public static function nice($nice, $id)
    {
        if (!is_array($id)) {
            $id = [$id];
        }

        return (boolean) static::updateAll(
            ['isnice' => (int) $nice],
            ['postid' => $id]
        );
    }

    /**
     * 锁定文章
     * @param  integer $lock 锁定状态0,1
     * @param  array|integer $id    文章id
     * @return boolean
     */
    public static function lock($lock, $id)
    {
        if (!is_array($id)) {
            $id = [$id];
        }

        return (boolean) static::updateAll(
            ['islock' => (int) $lock],
            ['postid' => $id]
        );
    }
}
