Len168 Yii 2 Advanced Application Demo
==========================================

len168是基于Yii 2 高级模块开发的学习demo, 是本人用于学习Yii 2开发的一个练手项目, 在些分享给大家, 希望对入门的同学有所帮助.


DIRECTORY STRUCTURE
-------------------

```
common
    config/              contains shared configurations
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
backend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains backend configurations
    controllers/         contains Web controller classes
    models/              contains backend-specific model classes
    runtime/             contains files generated during runtime
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
frontend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontend widgets
vendor/                  contains dependent 3rd-party packages
environments/            contains environment-based overrides
tests                    contains various tests for the advanced application
    codeception/         contains tests developed with Codeception PHP Testing Framework
```


REQUIREMENTS
------------

The minimum requirement by this application template that your Web server supports PHP 5.4.0.


INSTALLATION
------------

### Clone from Github

~~~
git clone 
~~~

### Composer

If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

You can then install the application using the following command:

~~~
php composer.phar global require "fxp/composer-asset-plugin:1.0.0"
php composer.phar install
~~~


GETTING STARTED
---------------

After you install the application, you have to conduct the following steps to initialize
the installed application. You only need to do these once for all.

1. 进入yoursite目录, 终端运行命令 `init` 初始化程序.
2. 配置数据库组件 `components['db']` 位于 `common/config/main-local.php` 文件中.
3. 终端执行数据迁移 `php yii migrate`. 初始化数据库.
4. 终端执行初始化管理员账号`php yii init/create-administrator`
5. Set document roots of your Web server:

- for frontend `/path/to/yoursite/frontend/web/` and using the URL `http://frontend/`
- for backend `/path/to/yoursite/backend/web/` and using the URL `http://backend/`

To login into the application, you need to first sign up, with any of your email address, username and password.
Then, you can login into the application with same email address and password at any time.
