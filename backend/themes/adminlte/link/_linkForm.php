<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TermCates */
/* @var $form yii\widgets\ActiveForm */

$this->title = '更新链接';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="term-cates-form white-box">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 128]) ?>
    <?= $form->field($model, 'type')->textInput(['maxlength' => 8]) ?>
    <?= $form->field($model, 'url')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'icon')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'sort')->textInput(['maxlength' => 8]) ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('common', 'Create'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?><br/>

</div>