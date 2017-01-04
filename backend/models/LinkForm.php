<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\Links;

/**
 * 链接表单模型
 */
class LinkForm extends Model
{

    public $linkid; //链接id
    public $title; //链接标题
    public $type; //分类
    public $url; //分类
    public $icon = ''; //图标
    public $sort = 0; //排序


    public function attributeLabel()
    {
        return [
            'linkid' => Yii::t('backend/link', 'Link ID'),
            'title'  => Yii::t('backend/link', 'Link Title'),
            'type'   => Yii::t('backend/link', 'Link Type'),
            'url'    => Yii::t('backend/link', 'Link Url'),
            'icon'   => Yii::t('backend/link', 'Link Icon'),
            'sort'   => Yii::t('backend/link', 'Link Sort'),
        ];
    }

    public function rules()
    {
        return [
            [['title', 'type'], 'required'],
            [['title', 'type', 'url', 'icon'], 'trim'],
            [['title', 'type', 'url', 'icon'], 'default', 'value' => ''],
            [['linkid', 'sort'], 'integer'],
            [['title'], 'string', 'max' => 64],
            [['url', 'icon'], 'string', 'max' => 255],
            [['type'], 'in', 'range' => [Links::LINK_TYPE_LOCAL, Links::LINK_TYPE_FRIENDLY]]
        ];
    }

    /**
     * 创建链接
     *
     * @return boolean
     */
    public function create()
    {
        if ($this->validate()) {
            //创建term
            $term = new Links();
            $term->link_title = $this->title;
            $term->link_type = $this->type;
            $term->link_url = $this->url;
            $term->link_icon = $this->icon;
            $term->link_sort = $this->sort;
            return $term->save(false);
        }
    }
}