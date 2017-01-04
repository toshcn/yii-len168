<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use common\models\ImageForm;

/* @var $this yii\web\View */
?>

<div class="image-modal" style="position:relative;">
    <div class="modal inmodal fade imagefram" id="post-images" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="...">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-frame">
                    <div class="modal-header no-p-b">
                        <button type="button" class="close" data-dismiss="modal"> <span aria-hidden="true">&times;</span>
                            <span class="sr-only">关闭</span>
                        </button>
                        <h4 class="modal-title p-b-10">模态框标题</h4>
                        <ul class="nav nav-tabs" style="border-bottom:0;">
                            <li class="" id="upload-image-head">
                                <a data-toggle="tab" href="#upload-image">上传图片</a>
                            </li>
                            <li class="active" id="images-store-head">
                                <a data-toggle="tab" href="#images-store">图像库</a>
                            </li>
                        </ul>
                    </div>
                    <div class="modal-body">
                        <div class="tab-content">
                            <div class="tab-pane" id="upload-image">
                                <div class="upload-image-box">
                                <?php $upload = ActiveForm::begin([
                                    'action' => Url::to(['ucenter/post/upload-image'], true),
                                    'id' => 'upload-image-form',
                                    'options' => ['class' => '']
                                ]);?>
                                    <?= $upload->field($imageModel, 'image', ['template' => '{input}'])->widget(FileInput::classname(), [
                                        'options' => ['multiple' => true],
                                        'pluginOptions' => [
                                            'allowedFileExtensions' => explode(', ', ImageForm::EXTENSIONS),
                                            // 需要预览的文件格式
                                            'previewFileType' => 'image',
                                            // 预览的文件
                                            'initialPreview' => '',
                                            'previewSettings' => ['image' => ['width' => "120px", 'height' => "auto"]],
                                            // 需要展示的图片设置，比如图片的宽度等
                                            'initialPreviewConfig' => '',
                                            // 是否展示预览图
                                            'initialPreviewAsData' => true,
                                            // 异步上传的接口地址设置
                                            'uploadUrl' => Url::toRoute(['/ucenter/post/upload-main-image']),
                                            // 异步上传需要携带的其他参数，比如商品id等
                                            'uploadExtraData' => [
                                                'pid' => $postid,
                                            ],
                                            'uploadAsync' => true,
                                            // 最少上传的文件个数限制
                                            'minFileCount' => 1,
                                            // 最多上传的文件个数限制
                                            'maxFileCount' => 10,
                                            // 是否显示移除按钮，指input上面的移除按钮，非具体图片上的移除按钮
                                            'showRemove' => true,
                                            // 是否显示上传按钮，指input上面的上传按钮，非具体图片上的上传按钮
                                            'showUpload' => true,
                                            //是否显示[选择]按钮,指input上面的[选择]按钮,非具体图片上的上传按钮
                                            'showBrowse' => true,
                                            // 展示图片区域是否可点击选择多文件
                                            'browseOnZoneClick' => true,
                                            // 如果要设置具体图片上的移除、上传和展示按钮，需要设置该选项
                                            'fileActionSettings' => [
                                                // 设置具体图片的查看属性为false,默认为true
                                                'showZoom' => false,
                                                // 设置具体图片的上传属性为true,默认为true
                                                'showUpload' => true,
                                                // 设置具体图片的移除属性为true,默认为true
                                                'showRemove' => false,
                                            ],
                                        ],
                                        // 一些事件行为
                                        'pluginEvents' => [
                                            // 上传成功后的回调方法，需要的可查看data后再做具体操作，一般不需要设置
                                            "fileuploaded" => "function (event, data, id, index) {
                                                var v = data.response;
                                                var newHtml = '<li id=\"search-image-'+v.id+'\" data-id=\"'+v.id+'\" data-title=\"'+v.title+'\" data-path=\"'+v.path+'\" data-name=\"'+v.name+'\" data-size=\"'+v.size+'\" data-width=\"'+v.width+'\" data-height=\"'+v.height+'\" data-suffix=\"'+v.suffix+'\" data-thumb-suffix=\"'+v.thumb_suffix+'\"  data-original=\"'+v.original+'\" data-time=\"'+v.created_at+'\">'+
                                                    '<img src=\"'+_imageHost+v.thumb_path+v.name+'_326x195'+v.thumb_suffix+'\" width=\"160\" height=\"96\" >'+
                                                    '</li>';
                                                $('#search-image-items').removeData('-13');
                                                $('[name=\"search[cate]\"]').val('-1');
                                                $('[name=\"search[update]\"]').val('3');
                                                $('#search-images-btn').trigger('click');
                                            }",
                                        ],
                                    ]);?>
                                <?php ActiveForm::end(); ?>
                                </div>
                            </div>
                            <div class="row tab-pane active images-store" id="images-store">
                                <div class="col-md-8 image-toolbar">
                                    <div class="form-group col-xs-12 col-sm-4 col-md-4">
                                        <select class="form-control" name="search[cate]">
                                            <option value="-1">全部图片</option>
                                            <option value="0">未使用图片</option>
                                            <option value="1">文章图片</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-xs-12 col-sm-4 col-md-4">
                                        <select class="form-control" name="search[update]">
                                            <option value="3">最近三个月内</option>
                                            <option value="3-6">三个月前</option>
                                            <option value="6-12">半年前</option>
                                            <option value=">12">一年以前</option>
                                            <option value="-1">全部</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-xs-12 col-sm-4 col-md-4">
                                        <button type="button" class="btn btn-success" id="search-images-btn" data-page="">搜索 <i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                                <ul class="col-md-8 list-inline image-items" id="search-image-items">
                                    <div class="align-center"><i class="fa fa-spinner fa-spin"></i></div>
                                </ul>

                                <div class="hidden-xs hidden-sm col-md-4 image-detail">
                                    <div id="image-detail-box" style="display:none">
                                        <h4 class="p-b-10">图片详情</h4>
                                        <div class="thumbnail-image p-b-10">
                                            <img class="" id="priview-img" src="" width="240" height="147" alt="">
                                        </div>
                                        <div class="thumbnail-detail p-b-10">
                                            <span class="bold clearfix" id="priview-name"></span>
                                            <span class="clearfix" id="priview-time"></span>
                                            <span class="clearfix" id="priview-px"></span>
                                            <a class="blue" id="priview-edit" href="javascript:;" data-id="">编辑</a>
                                            <a class="red hide" id="priview-del" href="javascript:;" data-id="">彻底删除</a>
                                        </div>
                                        <hr class="no-m-t">
                                        <div class="form-inline thumbnail-footer">
                                            <div class="form-group p-b-10">
                                                <label for="">URL</label>
                                                <input type="text" class="form-control" id="priview-url" placeholder="图像链接地址">
                                            </div>
                                            <div class="form-group p-b-10">
                                                <label for="">标题</label>
                                                <input type="text" class="form-control" id="priview-title" placeholder="标题">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="footer-toolbar align-center" id="upload-image-bar" style="display:none">
                            <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                        </div>
                        <div class="footer-toolbar align-center" id="chosen-image-bar">
                            <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                            <button type="button" class="btn btn-primary" id="push-main-image">设为主图</button>
                            <button type="button" class="btn btn-warning" id="push-image-to-post">插入文章</button>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <div class="modal inmodal fade imgedit-modal" id="imgedit-modal" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="...">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header no-p-b">
                    <button type="button" class="close" data-toggle="modal" data-target="#post-images" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">关闭</span>
                    </button>
                    <h4 class="modal-title p-b-10">编辑图像</h4>
                </div>
                <div class="row modal-body">
                    <div class="col-md-8 imgedit-panel">
                        <div class="imgedit-cropper">
                            <img class="img-thumbnail" id="imgedit-cropper" src="" alt="">
                        </div>
                    </div>
                    <div class="col-md-4 imgedit-changes">
                        <label>图片浏览</label>
                        <div class="imgedit-preview"></div>
                        <label>图片信息</label>
                        <div class="form-group">
                            <div class="input-group input-group-sm">
                                <label class="input-group-addon" for="dataWidth">宽</label>
                                <input type="text" class="form-control" id="dataWidth" placeholder="宽">
                                <span class="input-group-addon">px</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group input-group-sm">
                                <label class="input-group-addon" for="dataHeight">高</label>
                                <input type="text" class="form-control" id="dataHeight" placeholder="高">
                                <span class="input-group-addon">px</span>
                            </div>
                        </div>
                        <div class="form-group hide">
                            <div class="input-group input-group-sm">
                                <label class="input-group-addon" for="dataRotate">旋转</label>
                                <input type="text" class="form-control" id="dataRotate" placeholder="角度">
                                <span class="input-group-addon">度</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">标题：</label>
                            <input type="text" class="form-control" id="edit-image-title" maxlength="64">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-toolbar">
                        <div class="btn-group" role="group">
                            <button type="button" name="set-cropper" class="btn btn-sm btn-default" data-method="setDragMode" data-option="move" data-toggle="tooltip" data-placement="top" data-original-title="移动"><i class="fa fa-arrows"></i></button>
                            <button type="button" name="set-cropper" class="btn btn-sm btn-default" data-method="setDragMode" data-option="crop" data-toggle="tooltip" data-placement="top" data-original-title="裁剪选择"><i class="fa fa-crop"></i></button>
                        </div>
                        <div class="btn-group" role="group">
                            <button type="button" name="set-cropper" class="btn btn-sm btn-default" data-method="zoom" data-option="0.1" data-toggle="tooltip" data-placement="top" data-original-title="放大"><i class="fa fa-search-plus"></i></button>
                            <button type="button" name="set-cropper" class="btn btn-sm btn-default" data-method="zoom" data-option="-0.1" data-toggle="tooltip" data-placement="top" data-original-title="缩小"><i class="fa fa-search-minus"></i></button>
                            <button type="button" name="set-cropper" class="btn btn-sm btn-default" data-method="zoomTo" data-option="1" data-toggle="tooltip" data-placement="top" data-original-title="原比例">100%</button>
                        </div>

                        <div class="btn-group" role="group">
                            <button type="button" name="set-cropper" class="btn btn-sm btn-default" data-method="move" data-option="-10" data-secondOption="0" data-toggle="tooltip" data-placement="top" data-original-title="向左移动"><i class="fa fa-arrow-left"></i></button>
                            <button type="button" name="set-cropper" class="btn btn-sm btn-default" data-method="move" data-option="10" data-secondOption="0" data-toggle="tooltip" data-placement="top" data-original-title="向右移动"><i class="fa fa-arrow-right"></i></button>
                            <button type="button" name="set-cropper" class="btn btn-sm btn-default" data-method="move" data-option="0" data-secondOption="-10" data-toggle="tooltip" data-placement="top" data-original-title="向上移动"><i class="fa fa-arrow-up"></i></button>
                            <button type="button" name="set-cropper" class="btn btn-sm btn-default" data-method="move" data-option="0" data-secondOption="10" data-toggle="tooltip" data-placement="top" data-original-title="向下移动"><i class="fa fa-arrow-down"></i></button>
                        </div>

                        <div class="btn-group hide" role="group">
                            <button type="button" name="set-cropper" class="btn btn-sm btn-default" data-method="rotate" data-option="-45" data-toggle="tooltip" data-placement="top" data-original-title="逆时针"><i class="fa fa-rotate-left"></i></button>
                            <button type="button" name="set-cropper" class="btn btn-sm btn-default" data-method="rotate" data-option="45" data-toggle="tooltip" data-placement="top" data-original-title="顺时针"><i class="fa fa-rotate-right"></i></button>
                        </div>

                        <div class="btn-group hide" role="group">
                            <button type="button" name="set-cropper" class="btn btn-sm btn-default" data-method="scaleX" data-option="-1" data-toggle="tooltip" data-placement="top" data-original-title="水平翻转"><i class="fa fa-arrows-h"></i></button>
                            <button type="button" name="set-cropper" class="btn btn-sm btn-default" data-method="scaleY" data-option="-1" data-toggle="tooltip" data-placement="top" data-original-title="垂直翻转"><i class="fa fa-arrows-v"></i></button>
                        </div>

                        <div class="btn-group" role="group">
                            <button type="button" name="set-cropper" class="btn btn-sm btn-default" data-method="crop" data-toggle="tooltip" data-placement="top" data-original-title="裁剪"><i class="fa fa-check"></i></button>
                            <button type="button" name="set-cropper" class="btn btn-sm btn-default" data-method="clear" data-toggle="tooltip" data-placement="top" data-original-title="取消"><i class="fa fa-times"></i></button>
                            <button type="button" name="set-cropper" class="btn btn-sm btn-default" data-method="reset" data-toggle="tooltip" data-placement="top" data-original-title="重置"><i class="fa fa-refresh"></i></button>
                        </div>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-white" data-toggle="modal" data-target="#post-images" data-dismiss="modal">返回</button>
                        </div>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-info" id="save-crop">保存</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>