<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TermCates */
/* @var $form yii\widgets\ActiveForm */

$this->title = '文章分类';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="term-cates-form white-box">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 128]) ?>
    <?= $form->field($model, 'slug')->textInput(['maxlength' => 128]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'parent')->textInput() ?>
    <?= $form->field($model, 'termid', ['template' => '{input}'])->hiddenInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('common', $model->isNewRecord ? 'Create' : 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?><br/>

</div>