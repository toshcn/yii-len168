Len168 Yii 2 Advanced Application Demo
==========================================

len168是基于Yii 2 高级模块开发的学习demo, 是本人用于学习Yii 2开发的一个练手项目, 在些分享给大家, 希望对入门的同学有所帮助.


REQUIREMENTS
------------

PHP >= 5.6.0.


安装
----

### Clone from Github

~~~
git clone https://github.com/toshcn/yii-len168
~~~

### Composer

如果你没有安装 [Composer](http://getcomposer.org/), 请先安装
 [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

安装 [Composer](http://getcomposer.org/)后, 使用下面命令安装各种依赖:

~~~
php composer.phar global require "fxp/composer-asset-plugin:1.0.0"
php composer.phar install
~~~

### Beanstalkd
如果你没有安装 [Beanstalkd](http://kr.github.io/beanstalkd/), 请先安装
- Ubuntu `sudo apt-get install beanstalkd`
- CentOS `yum install beanstalkd`

开始安装
-------

根据下面的步骤配置.

1. 进入yoursite目录, 终端运行命令 `php init` 初始化程序.
2. 配置数据库组件 `components['db']` 位于 `common/config/main-local.php` 文件中.
3. 终端执行数据迁移 `php yii migrate`. 初始化数据库.
4. 终端执行初始化管理员账号`php yii init/create-administrator`
-
~~~
Create an administrator ...
Administrator userid: 1000
Administrator username: len168
Administrator email: len168@len168.com
Administrator password: 123456
Are your sure to create an Administrator? (yes|no) [no]:yes
成功创建超级管理员帐号
~~~
5. 终端执行初始化菜单`php yii init/create-menu`
6. 配置网站域名位于 `common/config/main-local.php`
- `define('DOMAIN', 'yoursite.com');`
7. WEB服务器配置域名指定前后台应用:
- WEB服务器如：nginx, apache请配置好伪静态.
- for frontend `/path/to/yoursite/frontend/web/` and using the URL `http://frontend/`
- for backend `/path/to/yoursite/backend/web/` and using the URL `http://backend/`

8. 终端执行`php yii worker`打开`Beanstalkd`消息队列.
9. 访问[前台:http:://frontend]
10. 访问[后台:http:://backend]

