<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */
namespace common\models;

use Yii;
use yii\helpers\Html;
use yii\base\Model;
use common\models\Posts;
use common\models\User;
use common\models\PostAttributes;
use common\models\Terms;

/**
 * 文章表单
 */
class PostForm extends Model
{


    public $postid;             //文章编号
    public $title;              //文章标题
    public $author;             //文章作者
    public $image;              //文章主图
    public $imageSuffix;        //文章主图
    public $content;            //文章内容
    public $categorys;          //分类 array
    public $description;        //文章描述
    public $originalUrl;        //文章转载的原地址
    public $copyright;          //文章版权
    public $spend;              //阅读需要花费(水晶/金币)的数量
    public $paytype;            //支付类型
    public $posttype;           //文章类型
    public $parent;             //关联的父文章
    public $iscomment;          //是否开放评论
    public $isopen;             //是否开放阅读
    public $ispay;              //是否需要支付
    public $tags;               //标签
    public $isdraft;            //是否是草稿

    private $_oldCategorys;     //旧的文章分类

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'postid'       => Yii::t('common/label', 'Post ID'),
            'title'        => Yii::t('common/label', 'Post Title'),
            'author'       => Yii::t('common/label', 'Author'),
            'image'        => Yii::t('common/label', 'Post Main Image'),
            'imageSuffix'  => Yii::t('common/label', 'Post Main Image Suffix'),
            'categorys'    => Yii::t('common/label', 'Categorys'),
            'content'      => Yii::t('common/label', 'Post Content'),
            'description'  => Yii::t('common/label', 'Post Description'),
            'originalUrl'  => Yii::t('common/label', 'Original Link'),
            'copyright'    => Yii::t('common/label', 'Copyright'),
            'spend'        => Yii::t('common/label', 'Spend For Read'),
            'paytype'      => Yii::t('common/label', 'Spend Type'),
            'posttype'     => Yii::t('common/label', 'Post Type'),
            'parent'       => Yii::t('common/label', 'Parent Post'),
            'iscomment'    => Yii::t('common/label', 'Open Comment'),
            'isopen'       => Yii::t('common/label', 'Open Read'),
            'ispay'        => Yii::t('common/label', 'Pay'),
            'tags'         => Yii::t('common/label', 'Post Tags'),
            'isdraft'      => Yii::t('common/label', 'Is Draft'),
        ];
    }

    /**
     * 验证规则
     */
    public function rules()
    {
        return [
            [['title', 'author', 'content'], 'required'],
            [['title', 'author', 'content', 'image', 'originalUrl', 'description', 'imageSuffix'], 'trim'],
            ['title', 'string', 'length' => [1, 45]],
            ['imageSuffix', 'string'],

            [['iscomment', 'isopen'], 'default', 'value' => Posts::YES],
            [['parent', 'spend', 'ispay', 'paytype'], 'default', 'value' => Posts::NO],

            ['posttype', 'default', 'value' => Posts::POST_TYPE_ORIGINAL],
            ['posttype', 'in', 'range' => [Posts::POST_TYPE_ORIGINAL, Posts::POST_TYPE_REPRINT, Posts::POST_TYPE_TRANSLATE]],

            [['postid', 'spend', 'parent', 'isdraft'], 'integer'],

            ['originalUrl', 'url'],
            [['originalUrl', 'image', 'description'], 'string', 'max' => 255],

            ['copyright', 'default', 'value' => Posts::COPYRIGHT_INDICATE_THE_SOURCE],
            ['copyright', 'in', 'range' => [Posts::COPYRIGHT_INDICATE_THE_SOURCE, Posts::COPYRIGHT_CONTACT_THE_AUTHOR, Posts::COPYRIGHT_FORBIDDEN_REPRINT]],

            ['paytype', 'in', 'range' => [Posts::SPEND_TYPE_POINT, Posts::SPEND_TYPE_CRYSTAL, Posts::SPEND_TYPE_GOLD]],

            [['iscomment', 'isopen', 'ispay'], 'in', 'range' => [Posts::NO, Posts::YES]],
            [['categorys', 'tags'], 'default', 'value' => []],
            ['tags', 'safe'],
            ['categorys', 'validatePostCategorys', 'params' => ['type' => Terms::CATEGORY_CATE]],
            ['title', 'validatePostTitle'],
        ];
    }

    /**
     * 文章分类验证器
     * This method serves as the inline validation for categorys.
     *
     * @param string $attribute 需要验证的属性
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePostCategorys($attribute, $params)
    {
        $term = new Terms();
        $this->$attribute = array_filter($this->$attribute);
        $last = count($this->$attribute) - 1;
        if ($this->$attribute[$last] == 0) {
            unset($this->$attribute[$last]);
        }
        foreach ($this->$attribute as $key => $cate) {
            if (!$term->getExistById($cate, $params['type'])) {
                $this->addError($attribute, Yii::t('common/sentence', 'This category is not exists'));
                break;
            }
        }
    }

    /**
     * 文章标题验证器
     * This method serves as the inline validation for title.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePostTitle($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $post = Posts::find()->where(['user_id' => Yii::$app->getUser()->getId(), 'title' => $this->$attribute]);
            $row = $this->postid ? $post->andWhere(['not in', 'postid', [$this->postid]])->one() : $post->one();

            if ($row) {
                $this->addError($attribute, Yii::t('common/sentence', 'This title is exists'));
            }
        }
    }

    /**
     * 发表文章
     * @return [type] [description]
     */
    public function createPost()
    {
        if ($this->validate()) {
            $post = new Posts(['scenario' => 'create']);
            $post->title = Html::encode($this->title);
            $post->user_id = Yii::$app->getUser()->getId();
            $post->author = Html::encode($this->author);
            $post->categorys = implode(',', $this->categorys);//新分类
            $post->image = Html::encode($this->image);
            $post->image_suffix = Html::encode($this->imageSuffix);
            $post->content = $this->content;
            $post->content_len = mb_strlen($this->content);
            $post->description = $this->description;
            $post->original_url = Html::encode($this->originalUrl);
            $post->copyright = $this->copyright;
            $post->spend = $this->spend;
            $post->paytype = $this->paytype;
            $post->posttype = $this->posttype;
            $post->parent = $this->parent;
            $post->iscomment = $this->iscomment;
            $post->isopen = $this->isopen;

            $post->ispay = $post->spend && $post->paytype ? Posts::YES : Posts::NO;
            $post->status = $this->isdraft ? Posts::STATUS_DRAFT : Posts::STATUS_ONLINE;

            $post->os = Yii::$app->getSession()->get('loginOS');
            $post->browser = Yii::$app->getSession()->get('loginBrowser');

            $post->created_at = date('Y-m-d H:i:s');
            $post->updated_at = $post->created_at;
            $post->content_len = mb_strlen($this->content);
            if ($post->save(false)) {
                //添加文章属性
                $attr = new PostAttributes();
                $attr->post_id = $post->postid;
                $attr->hp = Yii::$app->params['post.defaultHp'];
                if ($attr->save(false)) {
                    $this->postid = $post->postid;
                    $this->updatePostTag($post);
                    $this->updatePostCategory($post);
                    User::increasePost($post->user_id);//增加发表量
                    return true;
                }
                $post->delete();
            }
        }

        return false;
    }

    public function updatePost()
    {
        if ($this->validate()) {
            $post = new Posts(['scenario' => 'update']);
            $post = $post->findOne($this->postid);

            $this->_oldCategorys = $post->categorys;//记录旧的分类
            $post->title = Html::encode($this->title);
            $post->author = Html::encode($this->author);
            $post->categorys = implode(',', $this->categorys);//新分类
            $post->image = Html::encode($this->image);
            $post->image_suffix = Html::encode($this->imageSuffix);
            $post->content = $this->content;
            $post->content_len = strlen($this->content);
            $post->description = $this->description;
            $post->original_url = Html::encode($this->originalUrl);
            $post->copyright = $this->copyright;
            $post->spend = $this->spend;
            $post->paytype = $this->paytype;
            $post->posttype = $this->posttype;
            $post->parent = $this->parent;
            $post->iscomment = $this->iscomment;
            $post->isopen = $this->isopen;
            $post->ispay = $this->ispay;
            $post->ispay = $post->spend && $post->paytype ? Posts::YES : Posts::NO;
            $post->status = ($post->status != Posts::STATUS_ONLINE) && $this->isdraft ? Posts::STATUS_DRAFT : Posts::STATUS_ONLINE;
            $post->updated_at = date('Y-m-d H:i:s');
            if ($post->save(false)) {
                $this->updatePostTag($post);
                $this->updatePostCategory($post);
                return true;
            }
        }

        return false;
    }


    /**
     * 更新文章的标签
     * @param  &object $post 当前文章对象
     */
    private function updatePostTag(&$post)
    {
        /** @var array 旧标签数组 */
        $oldTags = [];
        $tags = $post->getTags()->asArray()->all();

        $relation = new TermRelations();
        $relation->objectid = $this->postid;
        $relation->type = TermRelations::OBJECT_TYPE_POST;

        foreach ($tags as $key => $tag) {
            $oldTags[] = $tag['termid'];
        }
        $this->tags = array_unique($this->tags);//去重
        $newTags = $this->addNewTagRelation($this->tags);
        $bindTags = array_diff($newTags, $oldTags);

        foreach ($oldTags as $i => $term) {
            //如果旧标签已不关联文章，删除它们的关联关系。
            if (!in_array($term, $newTags)) {
                $dbTrans= Yii::$app->db->beginTransaction();
                try {
                    Terms::findOne($term)->updateCounters(['counts' => -1]);
                    $relation->find()->where(['objectid' => $this->postid, 'term' => $term])->one()->delete();
                    $dbTrans->commit();
                } catch (Exception $e) {
                    $dbTrans->rollback();
                }
            }
        }

        $this->bindPostTag($bindTags);
    }

    /**
     * 添加新标签到terms表 类型为文章标签 [@see Terms::CATEGORY_TAG]
     * 添加前先到term表查找下此名称是否已存在，若已存在，保存此名称ID到temp临时数组
     * 若不存在，添加新记录到terms表
     *
     * @param   &array $tags 名称数组·[name1, name2, ……]
     * @return  array $temp 返回标签ID, terms表主键termid
     */
    private function addNewTagRelation(&$tags)
    {
        $term = new Terms();
        $temp = [];
        foreach ($tags as $key => $tag) {
            $row = $term->getTermByTitle($tag, Terms::CATEGORY_TAG);
            if ($row) {
                $temp[] = $row['termid'];
            } else {
                $tag = Html::encode($tag);
                $term->title = $tag;
                $term->slug = $tag;
                $term->catetype = Terms::CATEGORY_TAG;
                $term->isNewRecord = true;
                $term->termid = null;
                if ($term->save()) {
                    $temp[] = $term->termid;
                }
            }
        }
        return $temp;
    }

    /**
     * 绑定文章标签到term_relations表
     * @param  attray $tags 标签数组值为termid
     */
    private function bindPostTag(&$tags)
    {
        $term = new Terms();
        $relation = new TermRelations();
        $relation->objectid = $this->postid;
        $relation->type = TermRelations::OBJECT_TYPE_POST;
        //绑定新标签
        foreach ($tags as $key => $tag) {
            $tag = Html::encode($tag);
            if (!$relation->getExistRelation($this->postid, $tag)) {
                $dbTrans= Yii::$app->db->beginTransaction();
                try {
                    $relation->term = $tag;
                    $relation->isNewRecord = true;
                    $relation->save();
                    Terms::findOne($tag)->updateCounters(['counts' => 1]);
                    $dbTrans->commit();
                } catch (Expression $e) {
                    $dbTrans->rollback();
                }
            }
        }
    }

    /**
     * 更新文章的分类
     * @param  &object $post 当前文章对象
     */
    private function updatePostCategory(&$post)
    {
        $relation = new TermRelations();
        $relation->objectid = $this->postid;
        $relation->type = TermRelations::OBJECT_TYPE_POST;

        $oldCategorys = $this->_oldCategorys ? explode(',', $this->_oldCategorys) : [];

        foreach ($oldCategorys as $i => $term) {
            //如果旧分类已不关联文章，删除它们的关联关系。
            if (!in_array($term, $this->categorys)) {
                $dbTrans= Yii::$app->db->beginTransaction();
                try {
                    Terms::decreaseCount($term);
                    $relation->find()->where(['objectid' => $this->postid, 'term' => $term])->one()->delete();

                    unset($oldCategorys[$i]);
                    $dbTrans->commit();
                } catch (Exception $e) {
                    $dbTrans->rollback();
                }
            }
        }

        $bindCategorys = array_diff($this->categorys, $oldCategorys);
        $this->bindPostCategory($bindCategorys);
    }


    /**
     * 绑定文章分类
     * 绑定前先查找此分类是否已绑定，若未绑定过，绑定到term_relations表
     *
     * @param  attray $categorys 分类数组值为termid
     */
    private function bindPostCategory(&$categorys)
    {
        $term = new Terms();
        $relation = new TermRelations();
        $relation->objectid = $this->postid;
        $relation->type = TermRelations::OBJECT_TYPE_POST;
        //绑定新标签
        foreach ($categorys as $key => $term) {
            if (!$relation->getExistRelation($this->postid, $term)) {
                $dbTrans= Yii::$app->db->beginTransaction();
                try {
                    $relation->term = $term;
                    $relation->isNewRecord = true;
                    $relation->save();
                    Terms::increaseCount($term);
                    $dbTrans->commit();
                } catch (Expression $e) {
                    $dbTrans->rollback();
                }
            }
        }
    }
}
