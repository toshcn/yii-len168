<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PostsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="posts-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'postid') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'author') ?>

    <?= $form->field($model, 'categorys') ?>

    <?php // echo $form->field($model, 'image') ?>

    <?php // echo $form->field($model, 'image_suffix') ?>

    <?php // echo $form->field($model, 'content') ?>

    <?php // echo $form->field($model, 'content_len') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'original_url') ?>

    <?php // echo $form->field($model, 'copyright') ?>

    <?php // echo $form->field($model, 'spend') ?>

    <?php // echo $form->field($model, 'paytype') ?>

    <?php // echo $form->field($model, 'posttype') ?>

    <?php // echo $form->field($model, 'parent') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'islock') ?>

    <?php // echo $form->field($model, 'iscomment') ?>

    <?php // echo $form->field($model, 'isstick') ?>

    <?php // echo $form->field($model, 'isnice') ?>

    <?php // echo $form->field($model, 'isopen') ?>

    <?php // echo $form->field($model, 'ispay') ?>

    <?php // echo $form->field($model, 'isforever') ?>

    <?php // echo $form->field($model, 'isdie') ?>

    <?php // echo $form->field($model, 'os') ?>

    <?php // echo $form->field($model, 'browser') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
