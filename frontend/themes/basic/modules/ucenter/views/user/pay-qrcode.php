<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Users;
use frontend\assets\AvatarAsset;

/* @var $this yii\web\View */
$this->title = '收款二维码图';
$this->params['bodyClass'] = 'gray-bg';

AvatarAsset::register($this);
?>
<div class="wrapper user-wrapper w-center">
    <div class="row">
        <div class="col-md-3">
            <!-- myUserInfoView -->
            <?= $this->render('_myUserInfoView', ['userDetail' => $userDetail]); ?>

            <!-- _myCenterLeftMenuView -->
            <?= $this->render('_myCenterLeftMenuView'); ?>

        </div>
        <div class="col-md-9">
            <div class="user-center">
                <div class="ibox-title">
                    <h5>二维码图</h5>
                </div>
                <div class="ibox">
                    <div class="ibox-content editavatar pay-qrcode">
                        <div class="headupload">
                            <h5>收款二维码图</h5>
                            <p>收款二维码图会显示在文章页，以便别人打赏你的文章。</p>

                            <div class="headportrait">
                                <ul class="list-unstyled list-inline">
                                    <li>
                                        <img class="img-preview" src="<?= $userDetail['pay_qrcode'] ?>" height="300">
                                        <p>最大600*350</p>
                                    </li>
                                </ul>
                            </div>
                            <h5>设置我的收款二维码图</h5>
                            <p>请选择一个新收款二维码图进行上传编辑。<br>收款二维码图保存后，您可能需要刷新一下本页面(按F5键)，才能查看最新的效果</p>
                            <div class="row headimage-crop">
                                <div class="col-md-6">
                                    <div class="image-crop">
                                        <img id="image-crop" src="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h4>预览</h4>
                                    <div class="img-preview img-preview-sm" id="head-preview"></div>

                                </div>
                            </div>
                            <div class="upload-head align-center">
                                <div class="btn-group">
                                    <label title="上传新图" for="inputImage" class="btn btn-info">
                                        <input type="file" name="file" id="inputImage" class="hide">
                                        上传新图
                                    </label>
                                    <label title="保存" id="upload-pay-qrcode-btn" class="btn btn-info">保存</label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php \common\widgets\JsBlock::begin(); ?>
<script>
    $(document).ready(function(){

        var $headcrop = $("#image-crop");
        $headcrop.cropper({
            aspectRatio: 12/7,
            viewMode: 1,
            preview: "#head-preview",
            minContainerWidth:300,
            minContainerHeight:175,
            minCropBoxWidth:300,
            minCropBoxHeight:175,
            maxCropBoxWidth: 600,
            maxCropBoxHeight:350,
            done: function(data) {
                // Output the result data for cropping image.
            }
        });

        var $inputImage = $("#inputImage");
        if (window.FileReader) {
            $inputImage.change(function() {
                var fileReader = new FileReader(),
                        files = this.files,
                        file;

                if (!files.length) {
                    return;
                }

                file = files[0];

                if (/^image\/\w+$/.test(file.type)) {
                    fileReader.readAsDataURL(file);
                    fileReader.onload = function () {
                        $inputImage.val("");
                        $headcrop.cropper("reset", true).cropper("replace", this.result);
                    };
                } else {
                    showMessage("请选择图片.");
                }
            });
        } else {
            $inputImage.addClass("hide");
        }
        var btnStatic = true;
        $("#upload-pay-qrcode-btn").click(function() {
            if (!btnStatic) {return;}
            if (!$('#image-crop').attr('src')) {
                swal({title: '提示', text: '请选择一张图片!', type: "warning", confirmButtonText: "确定"});
            }
            $.post("<?= Url::to(['/ucenter/user/ajax-upload-pay-qrcode']) ?>", {"img": $headcrop.cropper('getCroppedCanvas').toDataURL()}, function(json) {
                if (json.ok == 1) {
                    swal({title: '提示', text: '已上传成功', type: "success", confirmButtonText: "确定"}, function() {
                        window.location.reload();
                    });
                } else {
                    swal({title: '提示', text: '保存失败！', type: "error", confirmButtonText: "确定"});
                }
            });
        });
    });
</script>
<?php \common\widgets\JsBlock::end();?>