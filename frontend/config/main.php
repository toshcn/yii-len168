<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
return [
    'id' => 'app-frontend',
    'name' => 'len168.com专注IT技术分享，分享快乐，学好IT你就168',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'ucenter' => [
            'class' => 'frontend\modules\ucenter\Module',
            'defaultRoute' => 'index',
        ],
    ],
    'components' => [
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@frontend/views' => '@frontend/themes/basic',
                    '@frontend/views/layouts' => '@frontend/themes/basic/layouts',
                    '@frontend/modules' => '@frontend/themes/basic/modules',
                ],
            ],
        ],
        'session' => [
            'class' => 'yii\web\CacheSession',
            'name' => 'len168-site',
            'cookieParams' => ['domain' => '.' . DOMAIN, 'lifetime' => 0],
            'timeout' => 3600,
        ],

        'urlManager' => [
            'class' => 'common\components\MutilpleDomainUrlManager',
            'hosts' => [
                'home' => 'http://' . DOMAIN_HOME,
                'backend' => 'http://' . DOMAIN_BACKEND,
                'api' => 'http://' . DOMAIN_API,
                'user' => 'http://' . DOMAIN_USER_CENTER,
                'login' => 'http://' . DOMAIN_LOGIN,
            ],
            'suffix' => '.html',
            'rules' => [
                //'login' => 'site/login',
            ],
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

    ],
    'params' => $params,
];
