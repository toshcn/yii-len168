<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\LinkSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="links-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'linkid') ?>

    <?= $form->field($model, 'link_title') ?>

    <?= $form->field($model, 'link_type') ?>

    <?= $form->field($model, 'link_url') ?>

    <?= $form->field($model, 'link_icon') ?>

    <?php // echo $form->field($model, 'link_sort') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('link', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('link', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
