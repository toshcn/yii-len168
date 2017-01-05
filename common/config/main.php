<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */


//配置 跨域SESSION 和 COOKIE
define('DOMAIN', 'yii.my');
define('DOMAIN_HOME', 'www.' . DOMAIN);
define('DOMAIN_BACKEND', 'admin.' . DOMAIN);
define('DOMAIN_API', 'api.' . DOMAIN);
define('DOMAIN_USER_CENTER', 'user.' . DOMAIN);
define('DOMAIN_LOGIN', 'login.' . DOMAIN);
return [
    'language'   => 'zh-CN',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'assetManager' => [
            'linkAssets' => true,
        ],
        //角色权限管理组件
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['admin', 'author'],
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'common\models\User',
            'identityCookie' => ['name' => '_identity', 'httpOnly' => true, 'domain' => '.' . DOMAIN],
            'enableAutoLogin' => false,
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
