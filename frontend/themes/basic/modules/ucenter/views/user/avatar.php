<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Users;
use frontend\assets\AvatarAsset;

/* @var $this yii\web\View */
$this->title = '修改个人头像';
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
                    <h5>个人头像</h5>
                </div>
                <div class="ibox">
                    <div class="ibox-content editavatar">
                        <div class="headupload">
                            <h5>当前我的头像</h5>
                            <p>如果您还没有设置自己的头像，系统会显示为默认头像，您需要自己上传一张新照片来作为自己的个人头像</p>

                            <div class="headportrait">
                                <ul class="list-unstyled list-inline">
                                    <li>
                                        <div><img class="img-circle" src="<?= $userDetail['head'] ?>" width="150"></div>
                                        <span>150×150</span>
                                    </li>
                                    <li>
                                        <div><img class="img-circle small" src="<?= $userDetail['head'] ?>" width="50"></div>
                                        <span>50×50</span>
                                    </li>
                                </ul>
                            </div>
                            <h5>设置我的新头像</h5>
                            <p>请选择一个新照片进行上传编辑。<br>头像保存后，您可能需要刷新一下本页面(按F5键)，才能查看最新的头像效果</p>
                            <div class="row headimage-crop">
                                <div class="col-md-6">
                                    <div class="image-crop">
                                        <img id="image-crop" src="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h4>头像预览</h4>
                                    <div class="img-preview img-preview-sm" id="head-preview"></div>

                                </div>
                            </div>
                            <div class="upload-head align-center">
                                <div class="btn-group">
                                    <label title="上传新图" for="inputImage" class="btn btn-info">
                                        <input type="file" name="file" id="inputImage" class="hide">
                                        上传新图
                                    </label>
                                    <label title="保存" id="upload-head-btn" class="btn btn-info">保存</label>
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
            aspectRatio: 1,
            viewMode: 1,
            preview: "#head-preview",
            minContainerWidth:300,
            minContainerHeight:300,
            minCropBoxWidth:150,
            minCropBoxHeight:150,
            maxCropBoxWidth: 200,
            maxCropBoxHeight:200,
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
        $("#upload-head-btn").click(function() {
            if (!btnStatic) {return;}
            if (!$('#image-crop').attr('src')) {
                swal({title: '提示', text: '请选择一张图片!', type: "warning", confirmButtonText: "确定"});
            }
            $.post("<?= Url::to(['/ucenter/user/ajax-upload-head']) ?>", {"img": $headcrop.cropper('getCroppedCanvas').toDataURL()}, function(json) {

                json = $.parseJSON(json);
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