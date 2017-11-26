<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nickname')->textInput(['maxlength' => 30]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'pwd')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'paypwd')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'isauth')->textInput() ?>

    <?= $form->field($model, 'islock')->textInput() ?>

    <?= $form->field($model, 'isdel')->textInput() ?>

    <?= $form->field($model, 'isactive')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend/user', 'Create') : Yii::t('backend/user', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
