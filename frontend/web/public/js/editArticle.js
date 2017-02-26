;jQuery(function($) {
    $(document).ready(function(){

        $('#post-form').on('click', '[name="editormd-image-file"]', function() {
            if (! $(this).parent().find('[name="_csrf"]').val()) {
                $(this).parent().append('<input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">');
            }
        });

        //设图片为封面图
        $('#push-main-image').on('click', function() {
            var selected = $('#search-image-items').find('li.selected').data();

            if (selected) {
                var img = $('#search-image-items').find('li.selected>img').prop('src');
                $('#privew-main-image').prop('src', img);
                $('[name="PostForm[image]"]').val(_imageHost + selected.thumb_path + selected.name);
                $('[name="PostForm[imageSuffix]"]').val(selected.thumb_suffix +'?v='+ selected.version);
                swal({title: "提示！", text: "已设为封面图！", type: "success", confirmButtonText: "确认"});

            } else {
                swal({title: "提示！", text: "请选择图片", confirmButtonText: "确认"});
            }
        });

        //选择图片
        $('#search-image-items').on('click', 'li', function() {
            $('#search-image-items').find('li').removeClass('selected');
            $(this).addClass('selected');
            var data = $(this).data();
            var url = $(this).children('img').attr('src');
            $('#priview-img').attr('src', url);
            $('#priview-img').data(data);
            $('#priview-name').text(data.name+data.suffix);
            var date = data.time.split('-');
            $('#priview-time').text(date[0]+'年'+date[1]+'月'+date[2]+'日');
            $('#priview-px').text(data.width+' x '+data.height+'像素');
            $('#priview-url').val(url);
            $('#priview-title').val(data.title);
            $('#priview-edit').attr('data-id', data.id);
            $('#priview-del').attr('data-id', data.id);
            $('#image-detail-box').show();
        });
        $('#set-main-image-btn').on('click', function() {
            $('#search-images-btn').trigger('click');
            $('#push-image-to-post').hide();
        });

        //文章标题字数统计
        $('#post-title').on('input', function() {
            var len = $(this).val().length;
            var can = 45 - len;
            can = can > 0 ? can : 0;
            $('#title-len').text(can);
            len > 0 ? $(this).parent().removeClass('has-error') : $(this).parent().addClass('has-error');
        });
        //文章描述字数统计
        $('#edit-desc').on('input', function() {
            var len = $(this).val().length;
            var can = 250 - len;
            can = can > 0 ? can : 0;
            $('#desc-len').text(can);
        });
        //转载文章显示原文链接输入框
        $('#post-type').on('change', function() {
            if ($(this).val() == 2)
                $('#original-url').removeClass('hide');
            else
                $('#original-url').addClass('hide');
        });
        //添加热门标签
        $('#post-tagcloud > a').on('click.selete-post-tag', function() {
            var id = $(this).attr('data-tag');
            var tag = $(this).text();
            var has = $('#selected-tag').find('[data-tag="'+id+'"]');
            if (has.text()) {
                $(this).toggleClass('cur');
                has.remove();
                $('#edit-post-tag').css('padding-left', $('#selected-tag').width()+8);
            } else if ($('#selected-tag>.post-tag-seleted').length < 5) {
                var html = '<label class="label label-sm label-warning post-tag-seleted" data-tag="'+id+'" title="点击删除"><input type="hidden" name="PostForm[tags][]" value="'+tag+'">'+tag+'</label>';
                $('#selected-tag').append(html);
                $('#edit-post-tag').css('padding-left', $('#selected-tag').width()+8);
                $(this).toggleClass('cur');
            }
        });
        //已选标签点击事件
        $('#selected-tag').on('click', '.post-tag-seleted', function() {
            var tag = $(this).attr('data-tag');
            if (tag) {//热门标签
                $('#post-tagcloud>[data-tag="'+tag+'"]').toggleClass('cur');
            }
            $(this).remove();
            $('#edit-post-tag').css('padding-left', $('#selected-tag').width()+8);
        });
        //输入标签生成，绑定blur事件
        $('#edit-post-tag').on('blur.input-post-tag', function() {
            var input = $(this).val().replace(/[^a-zA-Z\d\u4e00-\u9fa5\s_,，]/g, "");
            var len = $('#selected-tag>.post-tag-seleted').length;//已有标签个数
            if (input) {
                var arr = input.split(/[\s,，]/);
                var can = 5 - len;//还可输入标签数
                $(this).val('');
                $.each(arr, function(i, v) {
                    if (v && can > 0) {
                        can--;
                        var html = '<label class="label label-sm label-warning post-tag-seleted" title="点击删除"><input type="hidden" name="PostForm[tags][]" value="'+v+'">'+v+'</label>';
                        $('#selected-tag').append(html);
                    }
                });
                $('#edit-post-tag').css('padding-left', $('#selected-tag').width()+8);
            }
        });
        //显示更多标签
        $('#more-tag').on('click', function() {
            if ($(this).hasClass('more')) {
                $('#post-tagcloud').css('height', '24');
                $('#post-tagcloud').css('overflow', 'hidden');
            } else {
                $('#post-tagcloud').css('height', 'auto');
                $('#post-tagcloud').css('overflow', 'visible');
            }
            var h = $('#post-tagcloud').height() <= 24 ? 34 : $('#post-tagcloud').height();
            $('#hot-tag-docker').css('height', h);
            $(this).toggleClass('orange more');
        });
        //图像上传
        $('#upload-image-head').on('click', function() {
            $('#chosen-image-bar').hide();
            $('#upload-image-bar').show();
        });
        //上传文件
        $('#images-store-head').on('click', function() {
            $('#upload-image-bar').hide();
            $('#chosen-image-bar').show();
        });
    });

    var testEditormd = editormd;

    testEditormd.katexURL= {
        js  : "/public/vendor/KaTeX/dist/katex.min",  // default: //cdnjs.cloudflare.com/ajax/libs/KaTeX/0.3.0/katex.min
        css : "/public/vendor/KaTeX/dist/katex.min"   // default: //cdnjs.cloudflare.com/ajax/libs/KaTeX/0.3.0/katex.min
    };
    testEditormd("word-edit", {
        width: "100%",
        height: 640,
        toolbarIcons : function() {
             return ["undo", "redo", "|", "bold", "del", "italic", "quote", "ucwords", "uppercase", "lowercase", "|",
              "h1", "h2", "h3", "h4", "h5", "h6", "|", "table", "list-ul", "list-ol", "hr", "|",
              "link", "reference-link", "images", "code", "preformatted-text", "datetime", "emoji", "html-entities", "pagebreak", "|",
              "goto-line", "watch", "preview", "fullscreen", "clear", "search", "|", "help", "info"
              ];
        },
        toolbarIconsClass : {
            images : "fa-file-photo-o"  // 指定一个FontAawsome的图标类
        },
        lang : {
            toolbar : {
                images : "添加图片",
            }
        },
        // 自定义工具栏按钮的事件处理
        toolbarHandlers : {
            /**
             * @param {Object}      cm         CodeMirror对象
             * @param {Object}      icon       图标按钮jQuery元素对象
             * @param {Object}      cursor     CodeMirror的光标对象，可获取光标所在行和位置
             * @param {String}      selection  编辑器选中的文本
             */
            images : function(cm, icon, cursor, selection) {
                $('#post-images').modal('show');
                $('#push-image-to-post').show();
                $('#search-images-btn').trigger('click');

                //var cursor    = cm.getCursor();     //获取当前光标对象，同cursor参数
                //var selection = cm.getSelection();  //获取当前选中的文本，同selection参数
                $('#push-image-to-post').off('click.push-image-to-post');
                $('#push-image-to-post').on('click.push-image-to-post', function() {
                    var e = $('#search-image-items').find('li.selected').data();
                    if (e) {
                        var img = _imageHost + e.path + e.name + e.suffix + '?v=' + e.version;
                        var title = e.title;
                        // 替换选中文本，如果没有选中文本，则直接插入
                        cm.replaceSelection("!["+title+"](" + selection + img+")");
                        // 如果当前没有选中的文本，将光标移到要输入的位置
                        if(selection === "") {
                            cm.setCursor(cursor.line, cursor.ch + 1);
                        }
                        $('#post-images').modal('hide');
                    } else {
                        swal({title: "提示！", text: "请选择图片", confirmButtonText: "确认"});
                    }

                });

                // this == 当前editormd实例
                console.log("testIcon =>", this, cm, icon, cursor, selection);
            },
        },
        syncScrolling: "single",
        markdown         : '',
        tex              : true,
        tocm             : true,
        emoji            : false,
        //taskList         : true,
        codeFold         : true,
        searchReplace    : true,
        htmlDecode       : "style,script,iframe",
        flowChart        : true,
        sequenceDiagram  : true,
        imageUpload    : true,
        imageFormats   : ["jpg", "jpeg", "gif", "png"],
        imageUploadURL : "./upload-img",
        path:"/public/vendor/editor.md/lib/",
        onload : function() {
            var len = this.getMarkdown().replace(/[^\x00-\xff]/g,"aaa").length;
            $('#word-len').text(len);
            $('#word-can-len').text(65535-len);
        },
        onchange : function() {
            var len = this.getMarkdown().replace(/[^\x00-\xff]/g,"aaa").length;
            $('#word-len').text(len);
            $('#word-can-len').text(65535-len);
        },
    });
});