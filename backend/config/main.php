<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'defaultRoute' => 'system/index',
    'bootstrap' => ['log'],
    'components' => [
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@backend/views' => '@backend/themes/adminlte',
                    '@backend/views/layouts' => '@backend/themes/adminlte/layouts',
                ],
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'session' => [
            'cookieParams' => ['domain' => '.' . DOMAIN, 'lifetime' => 24 * 3600],
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
            'errorAction' => 'auth/error',
        ],
    ],
    'modules' => [
        'admin' => [
            'class' => 'mdm\admin\Module',
            'layout' => 'left-menu',//yii2-admin的导航菜单
            'mainLayout' => '@backend/themes/adminlte/layouts/main.php',
            'controllerMap' => [
                'assignment' => [
                   'class' => 'mdm\admin\controllers\AssignmentController',
                   'userClassName' => 'common\models\User',
                   'idField' => 'id',
                ],
                'user' => [
                    'class' => 'backend\controllers\UserController',
                ],
            ],
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
        ],
    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            //'admin/*',//允许所有人访问admin节点及其子节点
            //'gii/*'
        ],
    ],
    'params' => $params,
];
