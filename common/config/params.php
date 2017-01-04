<?php
return [
    'adminEmail' => 'toshcn@toshcn.com',
    'supportEmail' => 'support@example.com',
    'siteDomain' => 'len168.com',
    'siteCopyright' => 'Copyright len168.com © 2017',
    'httpProtocol' => 'http',
    'siteHost' => [
        'http' => 'http://www.yii.my',
        'https' => 'https://www.yii.my',
    ],
    'user.administratorRoleGroupId' => 10,//默认管理员组id:10管理员
    'user.defaultRoleGroupId' => 20,//默认会员组id:20作者
    'user.passwordResetTokenExpire' => 3600,
    'user.defaultRoleGroupId' => 20,//默认会员组id: 10管理员, 20作者
    'user.defaultAvatar' => '/public/img/avatar.jpg',//会员默认头像
    'login.maxFailes' => 5,//最大登录错误次数，超过此值设置登录时限

    'avatar.maxCutValue' => 150,//上传保存头像最大高宽px
    'avatar.defaultSuffix' => '.jpg',//上传保存头像的类型
    'avatar.dirName' => 'avatar',//会员头像目录名称
    'post.defaultHp' => 100,//文章默认生命值
    'post.pageSize' => 20,//文章列表分页数

    'image.basePath' => '@frontend/web',//图片上传基本路径别名
    'image.relativePath' => '/upload/img/',//图片上传路径image.basePath路径下
    'thumb.relativePath' => '/upload/thumb/',//图片上传路径image.basePath路径下
    'thumb.suffix' => '.jpg',//缩略图后缀
    'image.pageSize' => 20,//图片列表分页数

    'image.host' => 'http://yii.my',//图片域名
    'image.saveMaxWidth' => 1000,//图片最大宽
    'image.uploadMaxSize' => 1024 * 1024 * 500,
    'image.makeWater' => true,//是否添加水印
    'image.quality' => 90,//图片质量0-100
    'image.originalImageFlag' => '_original',//图片原图标志10个字符内
    'image.waterConfig' => [
        'type' => 'water',//水印类型water, text
        'image' => './public/img/water.png',//水印图片位置, 相对webroot
        'size' => [130, 20],//水印图片的宽高
        'text' => 'LEN168.COM',
        'location' => 'rightBottom', //水印位置 center居中 leftTop左上 rightTop右上 leftBottom左下 rightBottom右下
        'padding' => [10, 10],//水印偏离量[x, y]
        'fontFile' => '',
        'fontOptions' => [
            'size' => 12,
            'color' => 'fff',
            'angle' => 0
        ]
    ],

    'resetTokenExpire' => '20', //重置密码链接有效时间分钟
    'resendResetTokenExpire' => '5', //重发重置密码链接时间间隔分钟
    'inviteReSendTimeOut' => 30, //邀请重发时间间隔秒数
    'commentWealth' => [
        'apps_hp' => 1,//评论是点赞，评论hp 加1
        'opps_hp' => -1,//评论是吐槽，评论hp 减1
    ],
    'userWealth' => [
        'comment_hp' => 1,//评论成功，hp 加1
        'comment_gold' => 2,//评论成功，金币 加2
    ],
    'replyPageSize' => 2,//评论回复每页显示数量
];
