<?php
/**
 * @link http://www.shuimugua.com/
 * @copyright Copyright (c) 2015 shuimugua.com
 * @license 
 */

namespace common\validators;

use Yii;
use yii\validators\Validator;
use yii\validators\ValidationAsset;
use yii\web\JsExpression;
use yii\helpers\Json;

/**
 * MobileValidator validates that the attribute value is a mobile number.
 * 验证手机号（中国大陆86）
 * 
 * @author toshcn <toshcn@foxmail.com>
 * @since 0.1
 */
 class MobileValidator extends Validator
 {
    /**
     * @var string The mobile type, Defaults CN(China)
     */
    public $country = 'CN';
    /**
     * @var integer specifies the lenght limit of the value to be validated.
     * Defaults 11 is only of the China mobile.
     * @see notEqual for the customized message for a string that does not match desired length
     */
    public $lenght = 11;
    /**
     * @var string user-defined error message used when the value is not a mobile.
     */
    public $message;
    /**
     * @var array the regular expression for matching mobile.
     */
    public $mobilePattern = [
                'CN' => '/^(1[3-8][0-9])\d{8}$/',
            ];

    /**
     * [init description]
     * @return [type] [description]
     */
    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('common/validator', '{value} is not a mobile.');
        }
    }
    /**
     * [validateAttribute description]
     * @param  [type] $model     [description]
     * @param  [type] $attribute [description]
     * @return [type]            [description]
     */
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        if (is_array($value)) {
            $this->addError($model, $attribute, $this->message);
            return;
        }
        if (!preg_match($this->mobilePattern[$this->country], "$value")) {
            $this->addError($model, $attribute, $this->message);
        }
    }
    /**
     * [validateValue description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    protected function validateValue($value)
    {
        //make sure mobile number lenght is equal to [[lenght]]
        if (!is_numeric($value) || strlen($value) == $this->lenght) {
            return [$this->message, []];
        }
        if (!preg_match($this->mobilePattern[$this->country], "$value")) {
            return [$this->message, []];
        } else {
            return null;
        }
    }
    /**
     * [clientValidateAttribute description]
     * @param  [type] $model     [description]
     * @param  [type] $attribute [description]
     * @param  [type] $view      [description]
     * @return [type]            [description]
     */
    public function clientValidateAttribute($model, $attribute, $view)
    {
        $label = $model->getAttributeLabel($attribute);

        $options = [
            'pattern' => new JsExpression($this->mobilePattern[$this->country]),
            'message' => Yii::$app->getI18n()->format($this->message, [
                'attribute' => $label,
            ], Yii::$app->language),
        ];//var_dump($options);die;

        if ($this->skipOnEmpty) {
            $options['skipOnEmpty'] = 1;
        }

        ValidationAsset::register($view);
        return 'yii.validation.mobile(value, messages, ' . Json::htmlEncode($options) . ');';
    }
 }