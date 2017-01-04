<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\Terms;

/**
 * 链接分类表单模型
 */
class LinkCategoryForm extends Model
{

    public $termid; //分类id
    public $title; //分类标题
    public $slug; //分类别称
    public $description = ''; //描述
    public $parent = 0; //父节点 默认无节点

    private $_type = Terms::CATEGORY_LINK; //类型 '链接分类'

    public function attributeLabel()
    {
        return [
            'title'       => Yii::t('backend/link', 'Category Title'),
            'slug'        => Yii::t('backend/link', 'Category Slug'),
            'description' => Yii::t('backend/link', 'Category Description'),
            'parent'      => Yii::t('backend/link', 'Category Parent Node'),
        ];
    }

    public function rules()
    {
        return [
            ['title', 'required'],
            [['title', 'slug', 'description'], 'trim'],
            [['slug', 'description'], 'default', 'value' => ''],
            ['parent', 'default', 'value' => 0],
            [['termid', 'parent'], 'integer'],
        ];
    }

    /**
     * 创建分类
     *
     * @return boolean
     */
    public function create()
    {
        if ($this->validate()) {
            //创建term
            $term = new Terms();
            $term->title = $this->title;
            $term->slug = $this->slug ? $this->slug : $this->title;
            $term->parent = $this->parent;
            $term->description = $this->description;
            $term->catetype = $this->_type;
            return $term->save();
        }
    }
    /**
     * 更新分类
     *
     * @return boolean
     */
    public function update()
    {
        if ($this->validate()) {
            $term = Terms::findOne($this->termid);
            $term->title = $this->title;
            $term->slug = $this->slug ? $this->slug : $this->title;
            $term->parent = $this->parent;
            $term->description = $this->description;
            return $term->save();
        }
    }
}