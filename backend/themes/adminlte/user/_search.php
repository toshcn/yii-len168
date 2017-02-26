<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'uid') ?>

    <?= $form->field($model, 'group') ?>

    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'nickname') ?>

    <?= $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'head') ?>

    <?php // echo $form->field($model, 'mobile') ?>

    <?php // echo $form->field($model, 'sex') ?>

    <?php // echo $form->field($model, 'auth_key') ?>

    <?php // echo $form->field($model, 'password') ?>

    <?php // echo $form->field($model, 'reset_token') ?>

    <?php // echo $form->field($model, 'reset_token_expire') ?>

    <?php // echo $form->field($model, 'motto') ?>

    <?php // echo $form->field($model, 'hp') ?>

    <?php // echo $form->field($model, 'golds') ?>

    <?php // echo $form->field($model, 'crystal') ?>

    <?php // echo $form->field($model, 'posts') ?>

    <?php // echo $form->field($model, 'comments') ?>

    <?php // echo $form->field($model, 'friends') ?>

    <?php // echo $form->field($model, 'followers') ?>

    <?php // echo $form->field($model, 'os') ?>

    <?php // echo $form->field($model, 'browser') ?>

    <?php // echo $form->field($model, 'isauth') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'safe_level') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
