<?php
return [
    //yii2-admin 插件配置
    'mdm.admin.configs' => [
        'menuTable' => '{{%auth_menu}}',
        'userTable' => '{{%user}}',
    ],
    'httpProtocol' => 'http',
    'siteHost' => [
        'http' => 'http://www.yii.my',
        'https' => 'https://www.yii.my',
    ],

    'catch.time.topPosts' => 6, //首页置顶文章缓存时间秒
    'catch.time.categorys' => 6, //分类缓存时间秒
    'catch.time.post' => 6, //文章缓存时间秒
    'catch.time.postList' => 6, //文章列表缓存时间秒
    'catch.time.userComment' => 6, //评论缓存时间秒
    'catch.time.help' => 10, //分类缓存时间秒
    'catch.time.authorWidget' => 3, //会员资料缓存时间秒
];
