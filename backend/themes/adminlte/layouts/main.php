<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */


dmstr\web\AdminLteAsset::register($this);

backend\assets\BackendAsset::register($this);

$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<?php $this->beginBody() ?>
<div class="wrapper">

    <?= $this->render(
        'header.php',
        ['directoryAsset' => $directoryAsset]
    ) ?>

    <?= $this->render(
        'left.php',
        ['directoryAsset' => $directoryAsset]
    )
    ?>

    <?= $this->render(
        'content.php',
        ['content' => $content, 'directoryAsset' => $directoryAsset]
    ) ?>

</div>

<div class="modal fade" id="error-modal" tabindex="-1" role="dialog" aria-labelledby="error-modal-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="error-modal-content">
            <div class="modal-header">
                <a href="javascript:;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </a>
                <h4 class="modal-title text-danger"><b>错误！</b></h4>
            </div>
            <div class="modal-body text-center bg-danger" id="error-modal-body"><p>操作错误!</p></div>

            <div class="modal-footer text-center">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
