<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

return [
    'language'   => 'zh-CN',
    'timeZone'=>'Asia/Shanghai',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'assetManager' => [
            'linkAssets' => true,
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => [
                        YII_ENV_DEV ? 'jquery.js' : 'jquery.min.js',
                    ],
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [
                        YII_ENV_DEV ? 'css/bootstrap.css' : 'css/bootstrap.min.css',
                    ],
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => [
                        YII_ENV_DEV ? 'js/bootstrap.js' : 'js/bootstrap.min.js',
                    ],
                ],
                'frontend\assets\BasicAsset' => [
                    'css' => [
                        YII_ENV_DEV ? 'public/css/basic.css' : 'public/css/basic.min.css',
                    ],
                    'js' => [
                        YII_ENV_DEV ? 'public/js/basic.js' : 'public/js/basic.min.js',
                    ],
                ],
                'frontend\assets\SiteAsset' => [
                    'css' => [
                        YII_ENV_DEV ? 'public/css/site.css' : 'public/css/site.min.css',
                    ],
                    'js' => [
                        YII_ENV_DEV ? 'public/js/site.js' : 'public/js/site.min.js',
                    ],
                ],
                'frontend\assets\SiteIndexAsset' => [
                    'js' => [
                        YII_ENV_DEV ? 'public/js/jquery.smg.sliderpost.js' : 'public/js/jquery.smg.sliderpost.min.js',
                        YII_ENV_DEV ? 'public/js/index.js' : 'public/js/index.min.js',
                    ],
                ],
                'frontend\assets\ArticleAsset' => [
                    'js' => [
                        YII_ENV_DEV ? 'public/js/article.js' : 'public/js/article.min.js',
                    ],
                ],
            ],
        ],
        //角色权限管理组件
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['admin', 'author'],
        ],
        'i18n' => [
            'translations' => [
                'common*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/language',
                    'fileMap' =>[
                        'common' => 'common.php',
                        'common/sentence' => 'sentence.php',
                        'common/label' => 'label.php',
                    ],
                ],
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/language',
                ],
            ]
        ]
    ],
];
