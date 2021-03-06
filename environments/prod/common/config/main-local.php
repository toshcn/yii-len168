<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */


//配置 跨域SESSION 和 COOKIE
define('DOMAIN', '');
define('DOMAIN_HOME', 'www.' . DOMAIN);
define('DOMAIN_BACKEND', 'admin.' . DOMAIN);
define('DOMAIN_API', 'api.' . DOMAIN);
define('DOMAIN_USER_CENTER', 'user.' . DOMAIN);
define('DOMAIN_LOGIN', 'login.' . DOMAIN);

return [
    'timeZone' => 'Asia/Shanghai',//时区
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=yii2advanced',
            'username' => 'root',
            'password' => '123456',
            'tablePrefix'   => 'len168_',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // test ! send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.aliyun.com',
                'port' => 465,
                'encryption' => 'ssl',//tls or ssl
                'username' => 'len168@len168.com',
                'password' => '',
            ],
            'messageConfig' => [
                'charset' => 'UTF-8',
                'from' => ['len168@len168.com' => 'len168'],
            ],
        ],
        'cache' => [
           'class' => 'yii\caching\FileCache'
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'common\models\User',
            'identityCookie' => ['name' => '_identity', 'httpOnly' => true, 'domain' => '.' . DOMAIN],
            'enableAutoLogin' => false,
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'github' => [
                    'class' => 'yii\authclient\clients\GitHub',
                    'clientId' => '',
                    'clientSecret' => '',
                ],
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['trace', 'info', 'error', 'warning'],
                ],
            ],
        ],
        'beanstalk'=>[
            'class' => 'udokmeci\yii2beanstalk\Beanstalk',
            'host'=> "127.0.0.1", // default host
            'port'=>11300, //default port
            'connectTimeout'=> 1,
            'sleep' => false, // or int for usleep after every job
        ],
    ],
];
