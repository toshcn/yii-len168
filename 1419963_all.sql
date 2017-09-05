SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS  `user_info`;
CREATE TABLE `user_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) unsigned DEFAULT NULL COMMENT '外键user表id',
  `realname` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '真名',
  `mobile` bigint(20) NOT NULL DEFAULT '0' COMMENT '手机',
  `birthday` date NOT NULL COMMENT '生日',
  `country` smallint(6) unsigned DEFAULT '86' COMMENT '国家代码',
  `province` int(11) unsigned DEFAULT '0' COMMENT '省份代码',
  `city` int(11) unsigned DEFAULT '0' COMMENT '城市代码',
  `address` varchar(64) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '详细地址',
  `idcode` varchar(24) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '身份证',
  `created_at` datetime NOT NULL COMMENT '添加时间',
  `updated_at` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `fk-user_info-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`uid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

insert into `user_info`(`id`,`user_id`,`realname`,`mobile`,`birthday`,`country`,`province`,`city`,`address`,`idcode`,`created_at`,`updated_at`) values
('1','10000','len168','0','2017-03-25','86','0','0','','','2017-03-25 08:02:11','2017-03-28 12:37:56'),
('2','10001','toshcn','13717258761','2017-03-25','86','0','0','','','2017-03-25 08:03:09','2017-03-29 21:25:54'),
('3','10002','xzzhht','0','2017-03-27','86','0','0','','','2017-03-27 12:44:18','2017-03-27 12:44:18');
DROP TABLE IF EXISTS  `auth_rule`;
CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

insert into `auth_rule`(`name`,`data`,`created_at`,`updated_at`) values
('isAuthor','O:22:"common\\rbac\\AuthorRule":3:{s:4:"name";s:8:"isAuthor";s:9:"createdAt";i:1489937056;s:9:"updatedAt";i:1489937056;}','1489937056','1489937056'),
('userGroup','O:25:"common\\rbac\\UserGroupRule":3:{s:4:"name";s:9:"userGroup";s:9:"createdAt";i:1489937056;s:9:"updatedAt";i:1489937056;}','1489937056','1489937056');
DROP TABLE IF EXISTS  `menus`;
CREATE TABLE `menus` (
  `menuid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `object` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联的id',
  `menu_title` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '菜单名称',
  `menu_type` varchar(4) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '菜单类型term, post, link',
  `menu_parent` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父菜单',
  `menu_sort` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`menuid`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

insert into `menus`(`menuid`,`object`,`menu_title`,`menu_type`,`menu_parent`,`menu_sort`) values
('1','1','关于本站','link','0','0'),
('2','2','注册协议','link','0','0'),
('3','3','注册帮助','link','0','0'),
('4','7','web技术','term','0','0'),
('5','8','PHP','term','4','0'),
('6','9','Golang','term','4','1'),
('7','10','前端','term','4','2'),
('8','11','web框架','term','4','3'),
('9','12','<i class="fa fa-linux"></i> Linux','term','0','1'),
('10','13','Linux编程','term','9','0'),
('11','14','Linux运维','term','9','1'),
('12','15','<i class="fa fa-globe"></i> 资讯','term','0','2'),
('13','4','京东','link','0','0');
DROP TABLE IF EXISTS  `invites`;
CREATE TABLE `invites` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) unsigned DEFAULT NULL COMMENT '外键user表id',
  `email` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT '邀请的邮箱',
  `code` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '邀请码',
  `isuse` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已使用',
  `send_at` datetime NOT NULL COMMENT '发送邀请时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `code` (`code`),
  KEY `fk-invites-user_id` (`user_id`),
  CONSTRAINT `fk-invites-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`uid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS  `notices`;
CREATE TABLE `notices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) unsigned DEFAULT NULL COMMENT '被通知会员帐号',
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '通知标题',
  `content` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '通知内容',
  `isread` tinyint(1) DEFAULT '0' COMMENT '通知是否已阅读',
  `notice_at` datetime NOT NULL COMMENT '通知时间',
  PRIMARY KEY (`id`),
  KEY `fk-notices-user_id` (`user_id`),
  CONSTRAINT `fk-notices-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`uid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS  `terms`;
CREATE TABLE `terms` (
  `termid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `title` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '名称标题',
  `slug` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '名称英文标题',
  `catetype` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '分类category nav_menu post_tag link_category',
  `description` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `parent` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父级termid',
  `counts` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分类文章数',
  PRIMARY KEY (`termid`),
  KEY `index-terms-parent` (`parent`),
  KEY `index-terms-counts` (`counts`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

insert into `terms`(`termid`,`title`,`slug`,`catetype`,`description`,`parent`,`counts`) values
('1','顶部导航','顶部导航','nav_menu','','0','0'),
('2','底部导航','底部导航','nav_menu','','0','0'),
('3','未分类','未分类','category','','0','1'),
('4','帮助','help','category','帮助','0','3'),
('5','友情链接','firendLink','link_category','友情链接','0','0'),
('6','注册协议','注册协议','post_tag','','0','0'),
('7','web技术','web','category','web技术','0','1'),
('8','PHP','php','category','php','7','1'),
('9','Golang','golang','category','golang','7','0'),
('10','前端','前端','category','前端','7','0'),
('11','web框架','web框架','category','web框架','7','0'),
('12','Linux','linux','category','linux','0','3'),
('13','Linux编程','Linux编程','category','Linux编程','12','0'),
('14','Linux运维','Linux运维','category','Linux运维','12','3'),
('15','资讯','IT','category','IT资讯','0','0'),
('16','firewall','firewall','post_tag','','0','1'),
('17','防火墙','防火墙','post_tag','','0','1'),
('18','tengine','tengine','post_tag','','0','2'),
('19','nginx','nginx','post_tag','','0','2'),
('20','wordpress','wordpress','post_tag','','0','1');
DROP TABLE IF EXISTS  `posts`;
CREATE TABLE `posts` (
  `postid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) unsigned DEFAULT NULL COMMENT '外键user表id',
  `title` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '文章标题',
  `author` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '文章作者',
  `categorys` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '文章分类id列',
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '封面图',
  `image_suffix` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '封面图后缀',
  `content` text COLLATE utf8_unicode_ci COMMENT '文章内容',
  `content_len` int(11) NOT NULL DEFAULT '0' COMMENT '内容长度',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '文章描述',
  `original_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '转载文章原链接',
  `copyright` tinyint(1) NOT NULL DEFAULT '0' COMMENT '文章版权声明 0转载注明出处，1转载联系作者，2禁止转载',
  `spend` smallint(6) NOT NULL DEFAULT '0' COMMENT '阅读花费',
  `paytype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '花费类型1金币2水晶',
  `posttype` tinyint(1) NOT NULL DEFAULT '1' COMMENT '文章类型，1原创，2转载，3翻译',
  `parent` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文章父id',
  `status` smallint(6) NOT NULL DEFAULT '0' COMMENT '回收站-1,草稿0,发表1',
  `islock` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1锁定（无法阅读和评论），0否',
  `iscomment` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开放评论',
  `isstick` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否置顶',
  `isnice` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `isopen` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否公开',
  `ispay` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否需要付费',
  `isforever` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否免死',
  `isdie` tinyint(1) NOT NULL DEFAULT '0' COMMENT '文章死亡',
  `os` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '操作系统',
  `browser` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '浏览器',
  `created_at` datetime NOT NULL COMMENT '添加时间',
  `updated_at` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`postid`),
  UNIQUE KEY `index-posts-user_id-title` (`user_id`,`title`),
  KEY `index-posts-author` (`author`),
  KEY `index-posts-status` (`status`),
  CONSTRAINT `fk-posts-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`uid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

insert into `posts`(`postid`,`user_id`,`title`,`author`,`categorys`,`image`,`image_suffix`,`content`,`content_len`,`description`,`original_url`,`copyright`,`spend`,`paytype`,`posttype`,`parent`,`status`,`islock`,`iscomment`,`isstick`,`isnice`,`isopen`,`ispay`,`isforever`,`isdie`,`os`,`browser`,`created_at`,`updated_at`) values
('1','10000','关于本站','len168','4','','','[TOCM]

[TOC]

### 关于本站
1. LEN168是一个以分享IT技术为主的学习网站; 分享个人IT编程学习心得和体会,  码农的世界, 谁来谁知道!
2. 文章和评论请务必不要出现敏感词语，中华文化何其深，我们都辣么聪明，换着词语也能表达出意思来。恶意发文，恶意灌水，含有敏感词的，轻则删除，重禁言或注册账号。
3. 请使用Github账号注册登录本站。

#### 建站初衷
* 学习本应是一件快乐的事，快乐就要分享，所以分享你的学习吧！
* 帮助那些想学习编程的童鞋入门或提高他们的技术，也是功德无量！
	- 如果你帮助了一万个人，每人给你一百元你就百万身家了;
	- 如果你帮助了十万个人，每人给你十块钱你也百万身家了;
	- 如果你帮助了百万个人，每人给你一块钱你也百万身家了;
	- 如果你帮助了千万个人，每人随便给几块你都是千万富翁了;
	- 想想，天朝十几亿人啊，每年几亿学生啊！

#### 关于域名
> 本域名注册于2012年，想想这光景过得真快，当时用这个域名做了个导航网页类似hao123那种，记得到2014年后才关停，那时把域名迁入万网后就一直没用，直到现在用在本站。

#### 为什么么叫LEN168
> 我也很想叫163, 168的，没办法出生有点晚163, 168被人家抢先注册了。单词`learn`学习的意思，取其音就成`len`了，在多数编程语言里`strlen()函数`是计算字符串长度的，于是`len168`就这样被牵强在一起具有了`学习好编程就一路发`的意思！那为毛不叫`len163`呢？问得好，TMD`len163`也被人家抢先注册了。说多了都是泪啊。

#### 本站所用技术
> 本站使用阿里云ECS服务器，PHP7 + Nginx + 阿里云RDS, 启用了HTTP2.0， HTTPS，Google开源的Brotli压缩，是不是很高大上，所以最好使用Chrome浏览器浏览本站。详细如下列表：

| ECS  | CPU  | 内存 | 带宽 |
|-------|-------|-------|-------|
|[阿里云](https://s.click.taobao.com/t?e=m%3D2%26s%3DEVRpUwg%2BIfYcQipKwQzePCperVdZeJviEViQ0P1Vf2kguMN8XjClAp5%2FKo0iEGl0KGpkmy%2BPajYt4krvY6UkvRpBLI9vrRXzi2ZEQ%2B5PQO0PA0HvB2SRW%2Bdn1BbglxZYxUhy8exlzcpAFEHVckI7b93srg%2FL%2FeD3keUEnoKELDlWYetMiZZgV%2BSx6OrKqagyklzFeKMz7Cd4Qek9OyREeQ%2BdciYQJaT7LNmFwzcjFAU%3D)| 1核 | 1G    | 1M    |

| RDS  | CPU  | 内存 | 数据库 |
|-------|-------|-------|----------|
|[阿里云](https://s.click.taobao.com/t?e=m%3D2%26s%3DYPMkB%2BnADl4cQipKwQzePCperVdZeJviEViQ0P1Vf2kguMN8XjClAp5%2FKo0iEGl0Y%2BAWcwZharEt4krvY6UkvRpBLI9vrRXzCJb6nl%2FjB2ZOq7lw2JVQBedn1BbglxZYxUhy8exlzcq9AmARIwX9K%2BnbtOD3UdznPV1H2z0iQv9NkKVMHClW0R0UfDzlkg%2B9vjQXzzpXdTHGJe8N%2FwNpGw%3D%3D)| 2核 | 240MB |    Mysql5.6  |

| 开发语言  | HTTP服务器  | SSL证书 | 框架 |
|-------------|---------------- |-----------|-------|
| PHP7.0     |  Nginx          | Let\'s Encrypt  | Yii2.0 |

> 使用世界上最好的编程语言PHP

### 关于站长
* 当年在学校的课程虽然有C语言，C++，JAVA，还有VB，并自学过PHP，HTML ，CSS，其实很多就只看了下语法，压根没学到什么技术，当时一点不懂何为函数实参形参，值传递，引用传递……什么构造函数析构函数。天朝的教育大家懂滴，就是一些基础，你看不到实用在哪，一个win程序是怎么样做出来你根本不懂，一个网站是怎样做出来的也一点不懂，所以学习就没什么激情可言了。

* 那是2008年，花了家里不少的钱装了人生中第一台电脑, 当晚系统就被我搞崩了（汗）。当年刚好出了好多门事件，像什么YanZhaoMen， 固件门…… 当时宿舍几个也是买了电脑的，运气不好，哥的硬盘刚好是固件门中的产品，TMD 在一年里坏了四块硬盘（ST的硬盘一年包换）害得舍友都以为哥的下片搞坏硬盘的（宝宝心里苦啊）。然后电脑就成了看电影和玩游戏的高级玩具，编程这事是没学成了，回想当年宿舍几个一起玩CS、CF还是蛮有感慨的，要是当时有人从头到尾指引你怎样去学习编程，我想这一定是不一样的人生了。

* 当时有一些PHP的建站框架如php168，phpcms，dz啊，玩游戏忘学了。离开校园，踏进社会后才发现没技术没经验，你妹的，谁请你啊。当时找的第一个工作：电脑雕花学徒1k2，就是一个看机器的，干了不到十天（迷惘，抑郁，度日如年）不干了，打包袝回家。抑郁症是什么？我的那时感觉就是：不知道自己的人生在哪里，这世间无处安放自己，找不到人生的意义，不知道自己为什么要这着活着，内心很是煎熬。呵呵，很幸运，哥直接回老家（农村就是好啊，心情不好回去看看山看看水，人生活着还是有意义的嘛）。

* 说了那么多屁话还没说重点，呵呵，慢慢来不急。没过多久，家里出了点不好的事情，这都是命，然后没办法走上了做销售雨具的业务员，本想着从此与编程是路人了，过了几年，又出现了点不好的事情，一下子就不再干销售了。14年就去找做PHP的工作，其实上面我说的12年时用len168的域名做的网页就发挥了作用了，很多公司觉得你要是没技术没经验又不是名牌大学的，压根不会用你。其实哥也是有点幸运了，刚好面试了家正要做电子商城的公司，面试时人家问你做过什么学过什么，呵呵12年做的网页就有作用了，当然工资偶也开得低，人家就用偶了。其实当时技术真是很差，MVC都不懂的，没办法公司太小居然只有偶一个菜鸟技术员，没办法找了个开源的电子商城iweb就二次开发，花了一两个月改出了个大概能用的商城。也是这时接触ECS，直接把公司的商城用云服务器，省下了不少运维的时间。当时有空都是在学习PHP，了解到一些PHP框架像ThinkPHP，laravel， Yii等。度娘了好多资料，觉得TP文档比较友好适合国人，于是从TP入手自己试着为公司开发一个电子商城系统，就这着一边维护旧的商城系统，一边用TP开发新系统，直到16年，花了一年多点时间，把商城做出来了。两年里从还不怎么懂PHP的菜鸟，到了可以独立做出一个商城系统，收获还是很多的。
* 从15年偶就一直在学习Yii2，因为偶觉得Yii2适合企业做开发，人要有梦想不是么，不想当将军的士兵不是好士兵。然后就是看文档找资料，在这里要感谢一下yiichina，感谢一下digpage这两个网站，这两个网站的资料帮了不少学习Yii2的忙。学习Yii2的过程中给了偶很多感触，要想学好它最好的方法就了用它做出一个东西来，所以从15年就开始一边学习一边构思自己要做了一个什么样了的网站呢！15年底才开始写数据表结构，利用业余的时间，一点儿一点儿打磨，直到今天2017年3月25日（不对是26日）就是这篇《关于本站》偶就从25日写到26日了（无语了……写这句话是零辰56分，还是早上再写吧）。
* 书接上一回，话说做这个网站本着学习Yii2去的目的，其中重点的是学到了做一个网站最需要解决的事情是：表关系设计，还有就是美工了。美工真的很重要，看偶这个网站这么烂就知道啦。总结一下本人学习PHP的过程：
	 - 学校期间就学习了一下C语言这样，没什么编程经验。
	 - 然后自己到图书馆找了本PHP的书学习下语法，写个Hello World 而已。
	 - 离开校园后，有用过phpcms这种搭建过网站，就是改改几个页面的模板而已，都没涉到php的代码。
	 - 真正学习使用PHP是因为工作中使用iweb商城，二次开发不得不 看了源码，知道了MVC这种结构，学会了基本的SQL语法。
	 - 在有了以上技术经验后看了ThinkPHP的文档，试着使用TP开发网站，基本上参考了iweb商城的表结构，自己搞了个商城，搞了一年多，算是对PHP有了进一步了解，当然也只是停留在使用别人框架的地步，要自己写出一个像TP这样的框架是万万不能。
	 - 在做商城系统的同时，用点业余时间搞本网站，不停的熟悉着Yii2这个框架，很多时不懂的都是直接看着Yii2框架的源码搞懂的，16年让自己学会了什么是面向对象编程，其实偶不会告诉你偶16年看了《Linux 程序设计》，看了《golang入门指南》，看了《Linux内核的设计艺术》，也看了LFS-BOOK的。充实自己的技术理论还是很有必要的，即使还没有实践。
	 - 未来不是梦，学无止境，好好学习，天天向上。骚年你要努力啊！
	 - 人生格言
> 学好C语言走遍天下都不怕。

### 本站是否开源
* 是的，本站本着学习Yii2框架的目的，分享自己学习成果也是件快乐的事，本网源码将放到Github平台上，介时将放出链接。

### 支持本站
1. 间接支持
	- 如果各位要到某东某宝购物什么的，可从本站的链接进入，这样就支持到本站了（什么？这么简单）是的就是这么简单：
	 - [某东链接](https://union-click.jd.com/jdc?e=0&p=AyIPZRprFDJWWA1FBCVbV0IUEEULRFRBSkAOClBMW2VuOWZxa1EWEzgSeBcdPU04R0JQTyprVxkyFARXE1wRBxMAZRhcEwUVN2UbWiVJfAZlG2sVBxoFVRhSEQMQBl0YaxIyU1kQQwtKBBo3ZStr&t=W1dCFBBFC0RUQUpADgpQTFs%3D)
	 - [某宝链接](https://s.click.taobao.com/t?e=m%3D2%26s%3DY1IiG%2F%2F1TYQcQipKwQzePCperVdZeJviEViQ0P1Vf2kguMN8XjClAqXOTNh9ZRsxdLztEBlmupIt4krvY6UkvRpBLI9vrRXzi2ZEQ%2B5PQO35UqP3JAkofOo0BcZWWIRYYA%2FDpPH01wJAFEHVckI7b7Sgd9R%2Fv5WktY4Qt2cZ1lVeY%2By0blbhscYl7w3%2FA2kb)
2. 直接支持，随便打赏（推荐第一种，您硬要土豪也行）。
	- ![](https://www.len168.com/upload/img/10000/pay_qrcode/pay.jpg?v=1490506788)

### 人生有何意义
> 人的一生其实很短暂，我们每个人忙着工作赚钱养家糊口， 很少有空闲静下来做自己喜欢的事情，等我们想留下点什么给这个世界时，却发现，这历史没留下我们的印记，除了自己的子女如果有的话（<sup>~</sup><sub>!</sub> <sub>^</sub> <sub>!</sub><sup>~</sup>）。偶得留点什么给这个世界，不是么！','10419','LEN168是一个以分享IT技术为主的学习网站, web开发类如PHP, HTML5, CSS, jQuery, yii框架等; 系统类如Linux运维, nginx, apache, mysql数据库等, golang语言, C语言等; 各类IT技术教程; 分享个人IT编程学习心得和体会, 以编程为乐, 码农的世界, 谁来谁知道','','2','0','0','1','0','1','0','0','1','1','1','0','1','0','Linux','Chrome','2017-03-25 09:10:02','2017-06-07 18:25:13'),
('2','10000','用户注册协议','len168','4','','','###LEN168用户注册协议（草案）

欢迎您来到LEN168（以下简称‘本站’）。

请您仔细阅读以下条款，如果您对本协议的任何条款表示异议，您可以选择不进入本站。当您注册成功，无论是进入本站，还是在本站上发布任何内容（即「内容」），均意味着您（即「用户」）完全接受本协议项下的全部条款。

[TOCM]

[TOC]

###使用规则

1. 用户注册成功后，本站将给予每个用户一个用户帐号及相应的密码，该用户帐号和密码由用户负责保管；用户应当对以其用户帐号进行的所有活动和事件负法律责任。

2. 用户须对在本站的注册信息的真实性、合法性、有效性承担全部责任，用户不得冒充他人；不得利用他人的名义发布任何信息；不得恶意使用注册帐号导致其他用户误认；否则本站有权立即停止提供服务，收回其帐号并由用户独自承担由此而产生的一切法律责任。

3. 用户直接或通过各类方式（如 RSS 源和站外 API 引用等）间接使用本站服务和数据的行为，都将被视作已无条件接受本协议全部内容；若用户对本协议的任何条款存在异议，请停止使用本站所提供的全部服务。

4. 本站是一个信息分享、传播及获取的平台，用户通过本站发表的信息为公开的信息，其他第三方均可以通过本站获取用户发表的信息，用户对任何信息的发表即认可该信息为公开的信息，并单独对此行为承担法律责任；任何用户不愿被其他第三人获知的信息都不应该在本站上进行发表。

5. 用户承诺不得以任何方式利用本站直接或间接从事违反中国法律以及社会公德的行为，本站有权对违反上述承诺的内容予以删除。

6. 用户不得利用本站服务制作、上载、复制、发布、传播或者转载如下内容：
    + 反对宪法所确定的基本原则的；
    + 危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的；
    + 损害国家荣誉和利益的；
    + 煽动民族仇恨、民族歧视，破坏民族团结的；
    + 破坏国家宗教政策，宣扬邪教和封建迷信的；
    + 散布谣言，扰乱社会秩序，破坏社会稳定的；
    + 散布淫秽、色情、赌博、暴力、凶杀、恐怖或者教唆犯罪的；
    + 侮辱或者诽谤他人，侵害他人合法权益的；
    + 含有法律、行政法规禁止的其他内容的信息。

7. 本站有权对用户使用本站的情况进行审查和监督，如用户在使用本站时违反任何上述规定，本站或其授权的人有权要求用户改正或直接采取一切必要的措施（包括但不限于更改或删除用户张贴的内容、暂停或终止用户使用本站的权利）以减轻用户不当行为造成的影响。

###知识产权

+ 本站是一个信息获取、分享及传播的平台，我们尊重和鼓励本站用户创作的内容，认识到保护知识产权对本站生存与发展的重要性，承诺将保护知识产权作为本站运营的基本原则之一。

    + 用户在本站上发表的全部原创内容（包括但不仅限于回复、文章和评论），著作权均归用户本人所有。用户可授权第三方以任何方式使用，不需要得到本站的同意。
    + 本站上可由多人参与编辑的内容，包括但不限于问题及补充说明、答案总结、话题描述、话题结构，所有参与编辑者均同意，相关知识产权归本站所有。
    + 本站提供的网络服务中包含的标识、版面设计、排版方式、文本、图片、图形等均受著作权、商标权及其它法律保护，未经相关权利人（含本站及其他原始权利人）同意，上述内容均不得在任何平台被直接或间接发布、使用、出于发布或使用目的的改写或再发行，或被用于其他任何商业目的。
    + 为了促进知识的分享和传播，用户将其在本站上发表的全部内容，授予本站免费的、不可撤销的、非独家使用许可，本站有权将该内容用于本站各种形态的产品和服务上，包括但不限于网站以及发表的应用或其他互联网产品。
    + 第三方若出于非商业目的，将用户在本站上发表的内容转载在本站之外的地方，应当在作品的正文开头的显著位置注明原作者姓名（或原作者在本站上使用的帐号名称），给出原始链接，注明「发表于本站」，并不得对作品进行修改演绎。若需要对作品进行修改，或用于商业目的，第三方应当联系用户获得单独授权，按照用户规定的方式使用该内容。
    + 本站为用户提供「禁止转载」的选项。除非获得原作者的单独授权，任何第三方不得转载标注了「禁止转载」的内容，否则均视为侵权。
    + 在本站上传或发表的内容，用户应保证其为著作权人或已取得合法授权，并且该内容不会侵犯任何第三方的合法权益。如果第三方提出关于著作权的异议，本站有权根据实际情况删除相关的内容，且有权追究用户的法律责任。给本站或任何第三方造成损失的，用户应负责全额赔偿。
    + 如果任何第三方侵犯了本站用户相关的权利，用户同意授权本站或其指定的代理人代表本站自身或用户对该第三方提出警告、投诉、发起行政执法、诉讼、进行上诉，或谈判和解，并且用户同意在本站认为必要的情况下参与共同维权。
    + 本站有权但无义务对用户发布的内容进行审核，有权根据相关证据结合《侵权责任法》、《信息网络传播权保护条例》等法律法规及本站社区指导原则对侵权信息进行处理。


###个人隐私

+ 尊重用户个人隐私信息的私有性是本站的一贯原则，本站将通过技术手段、强化内部管理等办法充分保护用户的个人隐私信息，除法律或有法律赋予权限的政府部门要求或事先得到用户明确授权等原因外，本站保证不对外公开或向第三方透露用户个人隐私信息，或用户在使用服务时存储的非公开内容。同时，为了运营和改善本站的技术与服务，本站将可能会自行收集使用或向第三方提供用户的非个人隐私信息，这将有助于本站向用户提供更好的用户体验和服务质量。


###侵权举报

1. 处理原则

    + 本站作为知识分享社区，高度重视自由表达和个人、企业正当权利的平衡。依照法律规定删除违法信息是本站社区的法定义务，本站社区亦未与任何中介机构合作开展此项业务。

2. 受理范围

    + 受理本站社区内侵犯企业或个人合法权益的侵权举报，包括但不限于涉及个人隐私、造谣与诽谤、商业侵权。

        + 涉及个人隐私：发布内容中直接涉及身份信息，如个人姓名、家庭住址、身份证号码、工作单位、私人电话等详细个人隐私；
        + 造谣、诽谤：发布内容中指名道姓（包括自然人和企业）的直接谩骂、侮辱、虚构中伤、恶意诽谤等；
        + 商业侵权：泄露企业商业机密及其他根据保密协议不能公开讨论的内容。

3. 举报条件

+ 如果个人或单位发现本站上存在侵犯自身合法权益的内容，请与本站取得联系（邮箱：toshcn @ qq.com）。为了保证问题能够及时有效地处理，请务必提交真实有效、完整清晰的材料，否则不予受理。请使用以下格式（包括各条款的序号）：

    + 权利人对涉嫌侵权内容拥有商标权、著作权和/或其他依法可以行使权利的权属证明；如果举报人非权利人，请举报人提供代表企业进行举报的书面授权证明。
    + 充分、明确地描述侵犯了权利人合法权益的内容，提供涉嫌侵权内容在本站上的具体页面地址，指明涉嫌侵权内容中的哪些内容侵犯了上述列明的权利人的合法权益；
    + 权利人具体的联络信息，包括姓名、身份证或护照复印件（对自然人）、单位登记证明复印件（对单位）、通信地址、电话号码、传真和电子邮件；
    + 在侵权举报中加入如下关于举报内容真实性的声明：

        + 我本人为所举报内容的合法权利人；
        + 我举报的发布在本站社区中的内容侵犯了本人相应的合法权益；
        + 如果本侵权举报内容不完全属实，本人将承担由此产生的一切法律责任。

4. 处理流程

    + 出于网络社区的监督属性，并非所有申请都必须受理。本站自收到举报邮件十个工作日内处理完毕并给出回复。处理期间，不提供任何电话、邮件及其他方式的查询服务。

    + 出现本站已经删除或处理的内容，但是百度、谷歌等搜索引擎依然可以搜索到的现象，是因为百度、谷歌等搜索引擎自带缓存，此类问题本站无权也无法处理，因此相关申请不予受理。

    + 此为本站社区唯一的官方的侵权投诉渠道，暂不提供其他方式处理此业务。

    + 用户在本站中的商业行为引发的法律纠纷，由交易双方自行处理，与本站无关。

###免责申明

1. 本站不能对用户发表的回复或评论的正确性进行保证。
2. 用户在本站发表的内容仅表明其个人的立场和观点，并不代表本站的立场或观点。作为内容的发表者，需自行对所发表内容负责，因所发表内容引发的一切纠纷，由该内容的发表者承担全部法律及连带责任。本站不承担任何法律及连带责任。
3. 本站不保证网络服务一定能满足用户的要求，也不保证网络服务不会中断，对网络服务的及时性、安全性、准确性也都不作保证。
4. 对于因不可抗力或本站不能控制的原因造成的网络服务中断或其它缺陷，本站不承担任何责任，但将尽力减少因此而给用户造成的损失和影响。

###协议修改

1. 根据互联网的发展和有关法律、法规及规范性文件的变化，或者因业务发展需要，本站有权对本协议的条款作出修改或变更，一旦本协议的内容发生变动，本站将会直接在本站网站上公布修改之后的协议内容，该公布行为视为本站已经通知用户修改内容。本站也可采用电子邮件或私信的传送方式，提示用户协议条款的修改、服务变更、或其它重要事项。
2. 如果不同意本站对本协议相关条款所做的修改，用户有权并应当停止使用本站。如果用户继续使用本站，则视为用户接受本站对本协议相关条款所做的修改。','11038','欢迎您来到LEN168（以下简称‘本站’）。
请您仔细阅读以下条款，如果您对本协议的任何条款表示异议，您可以选择不进入本站。当您注册成功，无论是进入本站，还是在本站上发布任何内容（即「内容」），均意味着您（即「用户」）完全接受本协议项下的全部条款。','','2','0','0','1','0','1','0','0','1','1','1','0','1','0','Linux','Chrome','2017-03-25 09:28:18','2017-03-25 09:40:00'),
('3','10000','如何注册','len168','4','','','### 如何注册

1. 使用邀请码
	- 为什么要用这种方式？我感觉IT圈不同于其它，我们都是志同道合的一群骚年，没必要让一些智商没我们高的人混进来喷水（哈哈）。
	- 暂时不开放发送邀请码功能，是因为我还没想好。

2. 请使用Github账号注册本站。别问为什么，装B不好吗！','367','请使用Github账号注册本站','','2','0','0','1','0','1','0','0','0','1','1','0','1','0','Linux','Chrome','2017-03-26 14:43:44','2017-03-26 14:53:41'),
('4','10001','使用Firewalld防火墙管理你的web服务器端口','toshcn','12,14','https://www.len168.com/upload/thumb/10001/2017-03-26/c010dbe67a4474b0855ff2b53eba6d31','.jpg?v=1','[TOCM]

[TOC]
### firewalld简介
Centos从7.0版本开始系统默认使用firewalld防火墙，firewalld支持动态更新，无需重启服务即好完成配置，新增区域“zone”概念。

firewalld的字符界面管理工具是 firewall-cmd

firewalld默认配置文件/etc/firewalld/firewalld.conf
### firewalld安装
```shell
yum install firewalld firewall-config #安装
systemctl start firewalld.service     #启动
systemctl enable firewalld.service    #启用
systemctl stop firewalld.service      #停止
systemctl disable firewalld.service   #禁用
```
### 区域zone概念
* firewalld系统默认存在以下区域：
    - **drop：** 默认丢弃所有包
    - **block：** 拒绝所有外部连接，允许内部发起的连接
    - **public：** 指定外部连接可以进入
    - **external：** 这个不太明白，功能上和上面相同，允许指定的外部连接
    - **dmz：** 和硬件防火墙一样，受限制的公共连接可以进入
    - **work：** 工作区，概念和workgoup一样，也是指定的外部连接允许
    - **home：** 类似家庭组
    - **internal：** 信任所有连接

### firewall-cmd命令工具
```shell
firewall-cmd --state  # 显示状态
firewall-cmd --get-active-zones   # 查看区域信息
firewall-cmd --get-zone-of-interface=eth0 #查看指定网卡所属* 区域 正常为eth0,阿里云ECS为ech1 可用ifconfig查看
```

### 将网卡添加到区域，默认网卡都在public
``` shell
firewall-cmd --zone=public --permanent --add-interface=eth0  #正常
firewall-cmd --zone=public --permanent --add-interface=eth1 #阿里云ECS服务器

firewall-cmd --set-default-zone=public  #设置默认区域
```
### 开放指定端口
```shell
firewall-cmd --zone=public --add-port=80/tcp #开放80端口到public区域 重启生效
firewall-cmd --zone=public --add-port=8080/tcp #开放8080端口到public区域 重启生效
```

### 使更改生效
```shell
firewall-cmd --reload   #无需断开已连接的TCP连接 “热重启”
firewall-cmd --complete-reload  #需要断开连接已连接的TCP连接 “冷重启”
```','1487','Centos从7.0版本开始系统默认使用firewalld防火墙，firewalld支持动态更新，无需重启服务即好完成配置，新增区域“zone”概念。','','0','0','0','1','0','1','0','1','1','1','1','0','1','0','Linux','Chrome','2017-03-26 16:06:09','2017-03-26 16:06:09'),
('5','10001','Centos7下编译安装Tengine服务器','toshcn','12,14','https://www.len168.com/upload/thumb/10001/2017-03-26/41a2dd25293aedd90698bbb3229a444f','.jpg?v=2','[TOCM]

[TOC]
### 前言
Tengine是由淘宝网发起的Web服务器项目。它继承了Nginx所有优点，解决了Nginx所有缺点，在此基础上针对大访问量网站的需求，添加了很多高级功能和特性。Tengine的性能和稳定性已经在大型的网站如淘宝网，天猫商城等得到了很好的检验。

 - Tenginx开源官网：http://tengine.taobao.org/index_cn.html

- Tenginx开源GitHub：https://github.com/alibaba/tengine

### 更新你的系统
```shell
yum -y install update
```

### 安装编译工具
```shell
yum -y install gcc gcc-c++ autoconf automake　＃安装编译工具

yum -y install zlib zlib-devel openssl openssl-devel pcre pcre-devel　＃安装
```
1. 说明：
	- *devel包必需安装，包含了编译所需的头文件。
	- gcc gcc-c++： GNU编译器套件（GNU Compiler Collection）包括C、C++，也包括了这些语言的库（如libstdc++、libgcj等等）。GCC的初衷是为GNU操作系统专门编写的一款编译器。
	- zlib zlib-devel：提供数据压缩用的函数库。
	- openssl openssl-devel： 是一个强大的安全套接字层密码库，囊括主要的密码算法、常用的密钥和证书封装管理功能及SSL协议，并提供丰富的应用程序供测试或其它目的使用。
	- pcre pcre-devel： PCRE(Perl Compatible Regular Expressions)是一个Perl库，包括 perl 兼容的正则表达式库。

### 让tengine使用jemalloc管理内存
下载jemalloc 安装
```shell
cd /home  #进入home目录
wget https://github.com/jemalloc/jemalloc/releases/download/4.0.3/jemalloc-4.0.3.tar.bz2  #下载jemalloc
tar -xvf jemalloc-4.0.3.tar.bz2  #解压文件
cd jemalloc-4.0.3 #进入jemalloc文件夹 可使用命令 cat INSTALL 查看安装帮助文档

./configure 
make
make install #安装jemalloc 此命令需要管理员权限 su 或sudo提权 如 sudo make install
```
安装完成如下图：
![1000120170326170734](https://www.len168.com/upload/img/10001/2017-03-26/10897fd334fcf6b86ba60e0070c1e536.jpg?v=1)
> 安装jemalloc

启用jemalloc
```shell
echo \'/usr/local/lib\' > local.conf   #启用jemalloc
ldconfig   #让配置生效
```
### 安装Tengine
1. 下载tengine
```shell
cd /home  #进入home目录
wget http://tengine.taobao.org/download/tengine-2.1.1.tar.gz #下载tengine
tar -zxvf tengine-2.1.1.tar.gz  #解压
```
2. 创建Tengine运行所需的user会员 和 group会员组 以下命令需管理员权限
```shell
groupadd www-data  #创建名为www-data的普通会员组
useradd -g www-data www #创建名为www的会员并加入到www-data会员组
```
3. 安装
```shell
cd tengine-2.1.1   #进入tengine目录
./configure --user=www --group=www-data --with-jemalloc  
##让Tengine链接jemalloc库，运行时用jemalloc来分配和释放内存。
```
>![1000120170326171820](https://www.len168.com/upload/img/10001/2017-03-26/2c5e7cd8f3ce212d704a16776ae62730.jpg?v=1)

接上继续安装
```shell
make
make install
```
4. Tengine默认将安装在/usr/local/nginx目录。你可以用\'--prefix\'来指定你想要的安装目录。
大部分的选项跟Nginx是兼容的。下面列出的都是Tengine特有的选项。如果你想查看Tengine支持的所有选项，你可以运行\'./configure --help\'命令来获取帮助。
	- --dso-path
设置DSO模块的安装路径。
	- --dso-tool-path
设置dso_tool脚本本身的安装路径。
	- --without-dso
关闭动态加载模块的功能。
	- --with-jemalloc
让Tengine链接jemalloc库，运行时用jemalloc来分配和释放内存。
	- --with-jemalloc=path
设置jemalloc库的源代码路径，Tengine可以静态编译和链接该库。
5. make的目标选项
大部分的目标选项跟Nginx是兼容的。下面列出的是Tengine特有的选项。
	- make test
运行Tengine的测试用例。你首先需要安装perl来运行这个指令。
	- make dso_install
将动态模块的so文件拷贝到目标目录。这个目录可以通过\'--dso-path\'设置。默认是在Tengine安装目录下面的modules目录。

### 启用Tengine服务

1. Tengine默认将安装在/usr/local/nginx目录。你可以用\'--prefix\'来指定你想要的安装目录。
```shell
/usr/local/nginx/sbin/nginx -c /usr/local/nginx/conf/nginx.conf #启用Tengine服务
```
2. 查看Tengine服务是否运行
```shell
ps -ef|grep nginx
```
如下图：
![1000120170326171157](https://www.len168.com/upload/img/10001/2017-03-26/09f0245f3f0610a8108f5d9008975a4d.jpg?v=1)
> 查看tengine服务运行状态

### 完成安装
1. 修改web根目录权限，Tengine默认web目录在/usr/local/nginx/html

2. 修改web根目录权限 运行以下命令需要管理员权限
```shell
chown -R www:www-data /usr/local/nginx/html/ #把html目录所有者改为刚才添加的会员和会员组
```
在浏览器输入你服务器的IP地址，如下完成Tengine服务器安装。
![1000120170326170509](https://www.len168.com/upload/img/10001/2017-03-26/41a2dd25293aedd90698bbb3229a444f.jpg?v=2)
> 完成安装tengine

如果你的系统firewalld防火墙没有打开80端口 请查看 [《使用Firewalld防火墙管理你的web服务器端口》](https://www.len168.com/article/detail.html?id=4)一文。','5289','Tengine是由淘宝网发起的Web服务器项目。它继承了Nginx所有优点，解决了Nginx所有缺点，在此基础上针对大访问量网站的需求，添加了很多高级功能和特性','','0','0','0','1','0','1','0','1','1','1','1','0','1','0','Linux','Chrome','2017-03-26 17:14:33','2017-03-28 23:25:59'),
('6','10002','12312','xzzhht','3','','','123123123123权威爱上大声地','19','','','0','0','0','1','0','1','0','0','0','0','0','0','1','0','Windows','Chrome','2017-03-27 13:48:14','2017-03-27 13:48:14'),
('7','10001','设置Tengine/Nginx多域名多站点共享一台服务器','toshcn','12,14','https://www.len168.com/upload/thumb/10001/2017-03-29/c74e8cf309ab16a5abefd3fd3f3f9450','.jpg?v=1','[TOCM]

[TOC]
### 前言
今天给各位介绍下Tengine/Nginx服务器绑定多个域名多个网站的配置方法。

### Tengine/Nginx配置文件
- Tengine/Nginx配置文件在默认安装目录下的conf文件夹下，你也可以在终端下使用“whereis nginx”查找它的安装目录。
	查找nginx安装目录 如下我的nginx配置文件路径即为: /usr/local/nginx/conf/nginx.conf

```shell
[root@localhost ~]# whereis nginx
nginx: /usr/local/nginx
[root@localhost ~]#
```

##### Nginx的配置文件

```shell
vim  /usr/local/nginx/conf/nginx.conf  #打开nginx配置文件命令 你也可以使用你喜欢的编辑工具如vi emacs gedit
```
##### 原文件

```shell
#以下为Nginx 配置文件nginx.conf默认内容，已加注解。
user www www-data;  #以www会员和www-data会员组运行nginx
worker_processes 1; #最大进程数，一般设为cpu核心数量 如你是4核cpu 可以设为4

#error_log logs/error.log;     #指定错误日志文件路径，默认当前配置文件的父级目录logs下的error.log
#error_log logs/error.log notice; #指定错误日志文件路径并指定为只记录notice级别错误
#error_log logs/error.log info; #指定错误日志文件路径并指定为只记录info级别错误

#pid logs/nginx.pid; ##记录nginx运行时的进程ID
events {
 worker_connections 1024; #允许的最大连接数即tcp连接数
}

# load modules compiled as Dynamic Shared Object (DSO)
# 动态模块加载（DSO）支持。加入一个模块不再需要重新编译整个Tengine 这个是Tengine特有的
#dso {
# load ngx_http_fastcgi_module.so; #fastcgi模块
# load ngx_http_rewrite_module.so; #URL重写模块
#}

http {
 include mime.types; #设定mime类型,类型由conf目录下mime.type文件定义
 default_type application/octet-stream; #默认为 任意的二进制数据 

 ## 可配置日志格式: $remote_addr访客ip
 ## $remote_user已经经过Auth Basic Module验证的用户名
 ## $time_local访问时间
 ## $request请求的url  
 ## $body_bytes_sent 传送页面的字节数 $http_referer访问来源
 #log_format main \'$remote_addr - $remote_user [$time_local] "$request" \'
 # \'$status $body_bytes_sent "$http_referer" \'
 # \'"$http_user_agent" "$http_x_forwarded_for"\';

 #access_log logs/access.log main;  #访问记录日志

 sendfile on;  #开启高效文件传输模式 注意：如果图片显示不正常把这个改成off。
 #tcp_nopush on; #防止网络阻塞

 #keepalive_timeout 0;
 keepalive_timeout 65; #长连接超时时间，单位是秒

 #gzip on;  #开启gzip压缩

 server {#虚拟主机的配置
  listen 80; #监听80端口
  server_name localhost; #绑定域名可以有多个，用空格隔开
 #charset koi8-r; #字符编码 可设为 utf-8

 #access_log logs/host.access.log main; #访问记录日志

 location / { ##网站根目录设置在这里
  root html; #配置文件父级html目录，可以设到其它目录如/home/www目录，注意目录的所有者和权限 本文开头处user的信息
  index index.html index.htm; #默认索引文件，从左到右，如：index.php index.html index.htm 空格分开
 }

 #error_page 404 /404.html; #指定404错误文件位置 root指定目录下的404.html 以下50x文件同理

 # redirect server error pages to the static page /50x.html 
 #
 error_page 500 502 503 504 /50x.html;  ##服务器50x得的错误都跳转到html/50x.html文件 
 location = /50x.html {
   root html;
 }

 # proxy the PHP scripts to Apache listening on 127.0.0.1:80
 # 配置处理php文件，需要安装PHP
 #location ~ \\.php$ {
 # proxy_pass http://127.0.0.1;
 #}

 # pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000
 #
 #location ~ \\.php$ {
 # root html;
 # fastcgi_pass 127.0.0.1:9000;
 # fastcgi_index index.php;
 # fastcgi_param SCRIPT_FILENAME /scripts$fastcgi_script_name;
 # include fastcgi_params;
 #}

 # deny access to .htaccess files, if Apache\'s document root
 # concurs with nginx\'s one
 #
 #location ~ /\\.ht {
 # deny all;  ##禁止使用.htaccess文件
 #}
 }
 #
 #server {
 # listen 8000;
 # listen somename:8080;
 # server_name somename alias another.alias;

 # location / {
 # root html;
 # index index.html index.htm;
 # }
 #}
 # HTTPS server
 #
 #server {
 # listen 443 ssl;
 # server_name localhost;

 # ssl_certificate cert.pem;
 # ssl_certificate_key cert.key;

 # ssl_session_cache shared:SSL:1m;
 # ssl_session_timeout 5m;

 # ssl_ciphers HIGH:!aNULL:!MD5;
 # ssl_prefer_server_ciphers on;

 # location / {
 # root html;
 # index index.html index.htm;
 # }
 #}
 }
```

##### 修改后

```shell
###以下为修改过的nginx.conf配置文件，已加注解。
user www www-data;  #以www会员和www-data会员组运行nginx
worker_processes 1; #最大进程数，一般设为cpu核心数量 如你是4核cpu 可以设为4

error_log logs/error.log;     #指定错误日志文件路径，默认当前配置文件的父级目录logs下的error.log
#error_log logs/error.log notice; #指定错误日志文件路径并指定为只记录notice级别错误
#error_log logs/error.log info; #指定错误日志文件路径并指定为只记录info级别错误

pid logs/nginx.pid; ##记录nginx运行时的进程ID
events {
 use epoll; #新加 提高nginx的性能，限Linux下使用
 worker_connections 1024; #允许的最大连接数即tcp连接数
}

# load modules compiled as Dynamic Shared Object (DSO)
# 动态模块加载（DSO）支持。加入一个模块不再需要重新编译整个Tengine 这个是Tengine特有的
#dso {
# load ngx_http_fastcgi_module.so; #fastcgi模块
# load ngx_http_rewrite_module.so; #URL重写模块
#}

http {
 include mime.types; #设定mime类型,类型由conf目录下mime.type文件定义
 default_type application/octet-stream; #默认为 任意的二进制数据 

 ##可配置日志格式: $remote_addr访客ip
 ## $remote_user已经经过Auth Basic Module验证的用户名
 ## $time_local访问时间
 ## $request请求的url  
 ## $body_bytes_sent 传送页面的字节数
 ## $http_referer访问来源
 #log_format main \'$remote_addr - $remote_user [$time_local] "$request" \'
 # \'$status $body_bytes_sent "$http_referer" \'
 # \'"$http_user_agent" "$http_x_forwarded_for"\';

 #access_log logs/access.log main;  #访问记录日志

 sendfile on;  #开启高效文件传输模式 注意：如果图片显示不正常把这个改成off。
 #tcp_nopush on; #防止网络阻塞

 #keepalive_timeout 0;
 keepalive_timeout 65; #长连接超时时间，单位是秒

 gzip on;  #开启gzip压缩

 ## 新加 include 项引入/usr/local/nginx/vhosts/目录下所有.conf结尾的虚拟机配置文件
 include /usr/local/nginx/vhosts/*.conf
 }
 ```

##### 建立虚拟机配置目录
- 在nginx安装目录中新建目录虚拟机配置目录：vhosts

```shell
mkdir  /usr/local/nginx/vhosts/
```

##### 新建网站配置文件

```shell
vim /usr/local/nginx/vhosts/test.conf
```

```shell
##www.test.my 的配置文件
 server { #虚拟主机的配置
  listen 80; #监听80端口
  server_name www.test.my www.test.my; #绑定域名可以有多个，用空格隔开
 #charset koi8-r; #字符编码 可设为 utf-8
  charset utf-8;

 #access_log logs/host.access.log main; #访问记录日志

 location / { ##网站根目录设置在这里
 root html; #配置文件父级html目录，可以设到其它目录如/home/www目录，注意目录的所有者和权限 本文开头处user的信息
 index index.html index.htm; #默认索引文件，从左到右，如：index.php index.html index.htm 空格分开
 }

 #error_page 404 /404.html; #指定404错误文件位置 root指定目录下的404.html 以下50x文件同理

 # redirect server error pages to the static page /50x.html 
 #
 error_page 500 502 503 504 /50x.html; ##服务器50x得的错误都跳转到html/50x.html文件 
 location = /50x.html {
 root html;
 }

 # proxy the PHP scripts to Apache listening on 127.0.0.1:80
 # 配置处理php文件，需要安装PHP
 #location ~ \\.php$ {
 # proxy_pass http://127.0.0.1;
 #}

 # pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000
 #
 #location ~ \\.php$ {
 # root html;
 # fastcgi_pass 127.0.0.1:9000;
 # fastcgi_index index.php;
 # fastcgi_param SCRIPT_FILENAME /scripts$fastcgi_script_name;
 # include fastcgi_params;
 #}

 # deny access to .htaccess files, if Apache\'s document root
 # concurs with nginx\'s one
 #
 #location ~ /\\.ht {
 # deny all; ##禁止使用.htaccess文件
 #}
 }
 ```

##### 配置多个网站
- 同理，在vhosts目录下生成多个站点的配置文件即可完成多站点多域名共享一台主机。

```shell
#如再配置一个域名为 yuntheme.com站点的配置文件 
 vim /usr/local/nginx/vhosts/yuntheme.conf
 #复制刚才的test.conf的内容，只要像下面修改就行了
location / { ##网站根目录设置在这里
 root /usr/local/nginx/html/yuntheme; #修改此处
 index index.html index.htm;
 }
 ```
### 完成配置，重启Nginx

```shell
/usr/local/nginx/sbin/nginx -s reload
```','9272','今天给各位介绍下Tengine/Nginx服务器绑定多个域名多个网站的配置方法, Tengine/Nginx配置文件在默认安装目录下的conf文件夹下，你也可以在终端下使用“whereis nginx”查找它的安装目录。查找nginx安装目录 如下我的nginx配置文件路径即为: /usr/local/nginx/conf/nginx.conf','','0','0','0','1','0','1','0','1','1','1','1','0','1','0','Linux','Chrome','2017-03-29 21:28:14','2017-03-29 21:39:59'),
('8','10001','简单找回Wordpress登录密码','toshcn','7,8','https://www.len168.com/upload/thumb/10001/2017-03-29/ac43d70899cc5d09f297682a90e643de','.jpg?v=3','### 简单找回Wordpress登录密码

* 如果忘记了Wordpress登录密码，直接使用Wordpress登录后台的“找回密码”，输入你的管理员邮箱，就会收到重置密码的邮件了，点击重置链接，设置新的密码即可。这个前提是你安装Wordpress时配置好发送邮件功能。

* 打开你网站下的 wp-includes/class-phpass.php文件，修改如下：在263行
```php
function CheckPassword($password, $stored_hash)
{
	if ( strlen( $password ) > 4096 ) {
		return false;
	}

	$hash = $this->crypt_private($password, $stored_hash);
	if ($hash[0] == \'*\')
		$hash = crypt($password, $stored_hash);
		echo $hash;die;  //这是增加的代码
	return $hash === $stored_hash;
}
 ```

* 然后到登录页面登录你的管理员帐号，密码随便填一个，如123456, 然后点击登录，这时网页输出一串$P开头的字符串

	如：$P$BX5RTo9K0NXEbD37V30tTUL8UnkfXP2

* 用phpmyadmin等可以管理你数据库的工具，把数据库中的会员表(users) 对应会员的 user_pass 字段改为： $P$BX5RTo9K0NXEbD37V30tTUL8UnkfXP2  完成更改密码后，把刚才修改的文件改回原来的样子。

```php
function CheckPassword($password, $stored_hash)
{
	if ( strlen( $password ) > 4096 ) {
		return false;
	}

	$hash = $this->crypt_private($password, $stored_hash);
	if ($hash[0] == \'*\')
		$hash = crypt($password, $stored_hash);
		
	return $hash === $stored_hash;
}
 ```','1480','如果忘记了Wordpress登录密码，直接使用Wordpress登录后台的“找回密码”，输入你的管理员邮箱，就会收到重置密码的邮件了，点击重置链接，设置新的密码即可。这个前提是你安装Wordpress时配置好发送邮件功能','','0','0','0','1','0','1','0','1','0','1','1','0','1','0','Linux','Chrome','2017-03-29 22:18:49','2017-03-29 23:07:05');
DROP TABLE IF EXISTS  `post_reports`;
CREATE TABLE `post_reports` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `post_id` int(11) unsigned DEFAULT NULL COMMENT '外键posts表id',
  `tipster_id` int(11) unsigned DEFAULT NULL COMMENT '外键举报者id',
  `type` smallint(6) unsigned DEFAULT '0' COMMENT '举报的类型',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '额外描述',
  `created_at` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `fk-post_reports-post_id` (`post_id`),
  KEY `fk-post_replies-tipster_id` (`tipster_id`),
  CONSTRAINT `fk-post_replies-tipster_id` FOREIGN KEY (`tipster_id`) REFERENCES `user` (`uid`) ON DELETE CASCADE,
  CONSTRAINT `fk-post_reports-post_id` FOREIGN KEY (`post_id`) REFERENCES `posts` (`postid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS  `messages`;
CREATE TABLE `messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `sendfrom` int(11) unsigned DEFAULT NULL COMMENT '外键发信人id',
  `sendto` int(11) unsigned DEFAULT NULL COMMENT '外键收信人id',
  `content` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '内容',
  `isread` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否阅读',
  `isforever` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否永久保存',
  `send_at` datetime NOT NULL COMMENT '发送时间',
  PRIMARY KEY (`id`),
  KEY `fk-messages-sendfrom` (`sendfrom`),
  KEY `fk-messages-sendto` (`sendto`),
  CONSTRAINT `fk-messages-sendto` FOREIGN KEY (`sendto`) REFERENCES `user` (`uid`) ON DELETE CASCADE,
  CONSTRAINT `fk-messages-sendfrom` FOREIGN KEY (`sendfrom`) REFERENCES `user` (`uid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS  `followers`;
CREATE TABLE `followers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) unsigned DEFAULT NULL COMMENT '外键关注user_id',
  `follower_id` int(11) unsigned DEFAULT NULL COMMENT '外键粉丝user_id',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '关注状态1关注,0取消',
  `created_at` datetime NOT NULL COMMENT '关注时间',
  `updated_at` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `fk-followers-user_id` (`user_id`),
  KEY `fk-followers-follower_id` (`follower_id`),
  CONSTRAINT `fk-followers-follower_id` FOREIGN KEY (`follower_id`) REFERENCES `user` (`uid`) ON DELETE CASCADE,
  CONSTRAINT `fk-followers-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`uid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

insert into `followers`(`id`,`user_id`,`follower_id`,`status`,`created_at`,`updated_at`) values
('1','10001','10000','1','2017-03-29 23:04:00','2017-03-29 23:04:00');
DROP TABLE IF EXISTS  `post_attributes`;
CREATE TABLE `post_attributes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `post_id` int(11) unsigned DEFAULT NULL COMMENT '外键posts表id',
  `hp` int(11) NOT NULL DEFAULT '100' COMMENT '文章生命值',
  `golds` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '金币',
  `crystal` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '水晶',
  `views` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '浏览量',
  `comments` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '评论量',
  `apps` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '正方人数',
  `opps` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '反方人数',
  `neutrals` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '中立人数',
  PRIMARY KEY (`id`),
  KEY `index-posts-views` (`views`),
  KEY `index-posts-comments` (`comments`),
  KEY `fk-post_attributes-post_id` (`post_id`),
  CONSTRAINT `fk-post_attributes-post_id` FOREIGN KEY (`post_id`) REFERENCES `posts` (`postid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

insert into `post_attributes`(`id`,`post_id`,`hp`,`golds`,`crystal`,`views`,`comments`,`apps`,`opps`,`neutrals`) values
('1','1','100','0','0','46','0','0','0','0'),
('2','2','100','0','0','27','0','0','0','0'),
('3','3','100','0','0','24','0','0','0','0'),
('4','4','100','0','0','34','0','0','0','0'),
('5','5','101','0','0','36','1','1','0','0'),
('6','6','100','0','0','0','0','0','0','0'),
('7','7','100','0','0','30','0','0','0','0'),
('8','8','100','0','0','22','0','0','0','0');
DROP TABLE IF EXISTS  `commons`;
CREATE TABLE `commons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '公告标题',
  `content` text COLLATE utf8_unicode_ci NOT NULL COMMENT '公告内容',
  `isshow` tinyint(1) DEFAULT '0' COMMENT '是否显示',
  `created_at` datetime NOT NULL COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS  `reply_reports`;
CREATE TABLE `reply_reports` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `reply_id` int(11) unsigned DEFAULT NULL COMMENT '外键replies表id',
  `tipster_id` int(11) unsigned DEFAULT NULL COMMENT '外键举报者id',
  `type` smallint(6) unsigned DEFAULT '0' COMMENT '举报的类型',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '额外描述',
  `created_at` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `fk-reply_reports-reply_id` (`reply_id`),
  KEY `fk-reply_reports-tipster_id` (`tipster_id`),
  CONSTRAINT `fk-reply_reports-tipster_id` FOREIGN KEY (`tipster_id`) REFERENCES `user` (`uid`) ON DELETE CASCADE,
  CONSTRAINT `fk-reply_reports-reply_id` FOREIGN KEY (`reply_id`) REFERENCES `replies` (`replyid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS  `auth_menu`;
CREATE TABLE `auth_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `route` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `data` blob,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  CONSTRAINT `auth_menu_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_menu` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

insert into `auth_menu`(`id`,`name`,`parent`,`route`,`order`,`data`) values
('1','仪表盘',null,'/system/index',null,0x66612066612D64617368626F617264),
('2','外观',null,'/surface/index',null,0x66612066612D6465736B746F70),
('3','前台菜单','2','/surface/menus',null,0x66612066612D636F6C756D6E73),
('4','文章',null,'/post/index',null,0x66612066612D626F6F6B),
('5','全部文章','4','/post/index',null,0x66612066612D6C697374),
('6','文章分类','4','/post/category',null,0x66612066612D6D61702D6F),
('7','链接',null,'/link/index',null,0x66612066612D6C696E6B),
('8','全部链接','7','/link/index',null,0x66612066612D6C697374),
('9','链接分类','7','/link/category',null,0x66612066612D6D61702D6F),
('10','会员',null,'/user/index',null,0x66612066612D7573657273),
('11','全部会员','10','/user/index',null,0x66612066612D6C697374);
DROP TABLE IF EXISTS  `migration`;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

insert into `migration`(`version`,`apply_time`) values
('m000000_000000_base','1488730420'),
('m140506_102106_rbac_init','1489937056'),
('m140602_111327_create_menu_table','1489937056'),
('m161206_134531_site_table_init','1490428697');
DROP TABLE IF EXISTS  `user_login`;
CREATE TABLE `user_login` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) unsigned DEFAULT NULL COMMENT '外键user表id',
  `counts` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `failes` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '登录失败次数',
  `interval` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '登录间隔秒',
  `ipv4` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '登录IP',
  `last_ipv4` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最近登录IP',
  `failed_at` datetime DEFAULT NULL COMMENT '登录错误时间',
  `login_at` datetime NOT NULL COMMENT '登录时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `fk-user_login-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`uid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

insert into `user_login`(`id`,`user_id`,`counts`,`failes`,`interval`,`ipv4`,`last_ipv4`,`failed_at`,`login_at`) values
('1','10000','20','0','0','2005103719','2005103719',null,'2017-09-03 00:35:27'),
('2','10001','16','0','0','3755149646','3755149646',null,'2017-03-31 21:47:07'),
('3','10002','0','0','0','2342682581','0',null,'2017-03-27 12:44:18');
DROP TABLE IF EXISTS  `auth_assignment`;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

insert into `auth_assignment`(`item_name`,`user_id`,`created_at`) values
('admin','10000','1489937173'),
('admin','10001','1490428989');
DROP TABLE IF EXISTS  `comment_reports`;
CREATE TABLE `comment_reports` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `comment_id` int(11) unsigned DEFAULT NULL COMMENT '外键comments表id',
  `tipster_id` int(11) unsigned DEFAULT NULL COMMENT '外键举报者id',
  `type` smallint(6) unsigned DEFAULT '0' COMMENT '举报的类型',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '额外描述',
  `created_at` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `fk-comment_reports-comment_id` (`comment_id`),
  KEY `fk-comment_reports-tipster_id` (`tipster_id`),
  CONSTRAINT `fk-comment_reports-tipster_id` FOREIGN KEY (`tipster_id`) REFERENCES `user` (`uid`) ON DELETE CASCADE,
  CONSTRAINT `fk-comment_reports-comment_id` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`commentid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS  `menu`;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `route` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `data` blob,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `menu` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS  `links`;
CREATE TABLE `links` (
  `linkid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `link_title` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '链接名称',
  `link_type` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '链接类型local, friendly',
  `link_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'URL地址',
  `link_icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '链接图标',
  `link_sort` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`linkid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

insert into `links`(`linkid`,`link_title`,`link_type`,`link_url`,`link_icon`,`link_sort`) values
('1','关于本站','local','http://www.len168.com/help/aboute.html','','0'),
('2','注册协议','local','http://www.len168.com/help/registration-protocol.html','','0'),
('3','注册帮助','local','http://www.len168.com/help/how-to-register.html','','0'),
('4','京东','friendly','https://union-click.jd.com/jdc?e=0&p=AyIPZRprFDJWWA1FBCVbV0IUEEULRFRBSkAOClBMW2VuOWZxa1EWEzgSeBcdPU04R0JQTyprVxkyFARXE1wRBxMAZRhcEwUVN2UbWiVJfAZlG2sVBxoFVRhSEQMQBl0YaxIyU1kQQwtKBBo3ZStr&t=W1dCFBBFC0RUQUpADgpQTFs%3D','','0');
DROP TABLE IF EXISTS  `auth_item`;
CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

insert into `auth_item`(`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) values
('/*','2',null,null,null,'1489937056','1489937056'),
('/admin/*','2',null,null,null,'1490016214','1490016214'),
('/admin/assignment/*','2',null,null,null,'1490016214','1490016214'),
('/admin/assignment/assign','2',null,null,null,'1490016214','1490016214'),
('/admin/assignment/index','2',null,null,null,'1490016214','1490016214'),
('/admin/assignment/revoke','2',null,null,null,'1490016214','1490016214'),
('/admin/assignment/view','2',null,null,null,'1490016214','1490016214'),
('/admin/default/*','2',null,null,null,'1490016214','1490016214'),
('/admin/default/index','2',null,null,null,'1490016214','1490016214'),
('/admin/menu/*','2',null,null,null,'1490016214','1490016214'),
('/admin/menu/create','2',null,null,null,'1490016214','1490016214'),
('/admin/menu/delete','2',null,null,null,'1490016214','1490016214'),
('/admin/menu/index','2',null,null,null,'1490016214','1490016214'),
('/admin/menu/update','2',null,null,null,'1490016214','1490016214'),
('/admin/menu/view','2',null,null,null,'1490016214','1490016214'),
('/admin/permission/*','2',null,null,null,'1490016214','1490016214'),
('/admin/permission/assign','2',null,null,null,'1490016214','1490016214'),
('/admin/permission/create','2',null,null,null,'1490016214','1490016214'),
('/admin/permission/delete','2',null,null,null,'1490016214','1490016214'),
('/admin/permission/index','2',null,null,null,'1490016214','1490016214'),
('/admin/permission/remove','2',null,null,null,'1490016214','1490016214'),
('/admin/permission/update','2',null,null,null,'1490016214','1490016214'),
('/admin/permission/view','2',null,null,null,'1490016214','1490016214'),
('/admin/role/*','2',null,null,null,'1490016214','1490016214'),
('/admin/role/assign','2',null,null,null,'1490016214','1490016214'),
('/admin/role/create','2',null,null,null,'1490016214','1490016214'),
('/admin/role/delete','2',null,null,null,'1490016214','1490016214'),
('/admin/role/index','2',null,null,null,'1490016214','1490016214'),
('/admin/role/remove','2',null,null,null,'1490016214','1490016214'),
('/admin/role/update','2',null,null,null,'1490016214','1490016214'),
('/admin/role/view','2',null,null,null,'1490016214','1490016214'),
('/admin/route/*','2',null,null,null,'1490016214','1490016214'),
('/admin/route/assign','2',null,null,null,'1490016214','1490016214'),
('/admin/route/create','2',null,null,null,'1490016214','1490016214'),
('/admin/route/index','2',null,null,null,'1490016214','1490016214'),
('/admin/route/refresh','2',null,null,null,'1490016214','1490016214'),
('/admin/route/remove','2',null,null,null,'1490016214','1490016214'),
('/admin/rule/*','2',null,null,null,'1490016214','1490016214'),
('/admin/rule/create','2',null,null,null,'1490016214','1490016214'),
('/admin/rule/delete','2',null,null,null,'1490016214','1490016214'),
('/admin/rule/index','2',null,null,null,'1490016214','1490016214'),
('/admin/rule/update','2',null,null,null,'1490016214','1490016214'),
('/admin/rule/view','2',null,null,null,'1490016214','1490016214'),
('/admin/user/*','2',null,null,null,'1490016214','1490016214'),
('/admin/user/activate','2',null,null,null,'1490016214','1490016214'),
('/admin/user/ajax-status','2',null,null,null,'1490016214','1490016214'),
('/admin/user/change-password','2',null,null,null,'1490016214','1490016214'),
('/admin/user/create','2',null,null,null,'1490016214','1490016214'),
('/admin/user/delete','2',null,null,null,'1490016214','1490016214'),
('/admin/user/error','2',null,null,null,'1490016214','1490016214'),
('/admin/user/index','2',null,null,null,'1490016214','1490016214'),
('/admin/user/login','2',null,null,null,'1490016214','1490016214'),
('/admin/user/logout','2',null,null,null,'1490016214','1490016214'),
('/admin/user/request-password-reset','2',null,null,null,'1490016214','1490016214'),
('/admin/user/reset-password','2',null,null,null,'1490016214','1490016214'),
('/admin/user/signup','2',null,null,null,'1490016214','1490016214'),
('/admin/user/update','2',null,null,null,'1490016214','1490016214'),
('/admin/user/view','2',null,null,null,'1490016214','1490016214'),
('/auth/*','2',null,null,null,'1490016214','1490016214'),
('/auth/error','2',null,null,null,'1490016214','1490016214'),
('/auth/index','2',null,null,null,'1490016214','1490016214'),
('/auth/login','2',null,null,null,'1490016214','1490016214'),
('/auth/logout','2',null,null,null,'1490016214','1490016214'),
('/gridview/*','2',null,null,null,'1490016214','1490016214'),
('/gridview/export/*','2',null,null,null,'1490016214','1490016214'),
('/gridview/export/download','2',null,null,null,'1490016214','1490016214'),
('/link/*','2',null,null,null,'1490016214','1490016214'),
('/link/ajax-search-links','2',null,null,null,'1490016214','1490016214'),
('/link/category','2',null,null,null,'1490016214','1490016214'),
('/link/create','2',null,null,null,'1490016214','1490016214'),
('/link/delete','2',null,null,null,'1490016214','1490016214'),
('/link/error','2',null,null,null,'1490016214','1490016214'),
('/link/index','2',null,null,null,'1490016214','1490016214'),
('/link/update','2',null,null,null,'1490016214','1490016214'),
('/link/view','2',null,null,null,'1490016214','1490016214'),
('/main/*','2',null,null,null,'1490016214','1490016214'),
('/main/error','2',null,null,null,'1490016214','1490016214'),
('/post/*','2',null,null,null,'1490016214','1490016214'),
('/post/ajax-lock','2',null,null,null,'1490016214','1490016214'),
('/post/ajax-nice','2',null,null,null,'1490016214','1490016214'),
('/post/ajax-search-posts','2',null,null,null,'1490016214','1490016214'),
('/post/ajax-stick','2',null,null,null,'1490016214','1490016214'),
('/post/category','2',null,null,null,'1490016214','1490016214'),
('/post/error','2',null,null,null,'1490016214','1490016214'),
('/post/index','2',null,null,null,'1490016214','1490016214'),
('/post/update-category','2',null,null,null,'1490016214','1490016214'),
('/site/*','2',null,null,null,'1490016214','1490016214'),
('/site/error','2',null,null,null,'1490016214','1490016214'),
('/site/index','2',null,null,null,'1490016214','1490016214'),
('/site/login','2',null,null,null,'1490016214','1490016214'),
('/site/logout','2',null,null,null,'1490016214','1490016214'),
('/surface/*','2',null,null,null,'1490016214','1490016214'),
('/surface/ajax-create-menu','2',null,null,null,'1490016214','1490016214'),
('/surface/ajax-search-cates','2',null,null,null,'1490016214','1490016214'),
('/surface/edit-nav-menu','2',null,null,null,'1490016214','1490016214'),
('/surface/error','2',null,null,null,'1490016214','1490016214'),
('/surface/index','2',null,null,null,'1490016214','1490016214'),
('/surface/menus','2',null,null,null,'1490016214','1490016214'),
('/system/*','2',null,null,null,'1490016214','1490016214'),
('/system/error','2',null,null,null,'1490016214','1490016214'),
('/system/general','2',null,null,null,'1490016214','1490016214'),
('/system/index','2',null,null,null,'1490016214','1490016214'),
('/user/*','2',null,null,null,'1490016214','1490016214'),
('/user/ajax-status','2',null,null,null,'1490016214','1490016214'),
('/user/create','2',null,null,null,'1490016214','1490016214'),
('/user/delete','2',null,null,null,'1490016214','1490016214'),
('/user/error','2',null,null,null,'1490016214','1490016214'),
('/user/index','2',null,null,null,'1490016214','1490016214'),
('/user/login','2',null,null,null,'1490016214','1490016214'),
('/user/logout','2',null,null,null,'1490016214','1490016214'),
('/user/update','2',null,null,null,'1490016214','1490016214'),
('/user/view','2',null,null,null,'1490016214','1490016214'),
('admin','1',null,'userGroup',null,'1489937056','1489937056'),
('author','1',null,'userGroup',null,'1489937056','1489937056'),
('createPost','2','创建文章',null,null,'1489937056','1489937056'),
('updateOwnPost','2','更新自己的文章','isAuthor',null,'1489937056','1489937056'),
('updatePost','2','更新文章',null,null,'1489937056','1489937056');
DROP TABLE IF EXISTS  `auth_item_child`;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

insert into `auth_item_child`(`parent`,`child`) values
('admin','/*'),
('admin','author'),
('author','createPost'),
('author','updateOwnPost'),
('admin','updatePost'),
('updateOwnPost','updatePost');
DROP TABLE IF EXISTS  `wealth_records`;
CREATE TABLE `wealth_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) unsigned DEFAULT NULL COMMENT '外键user表id',
  `value` int(11) NOT NULL DEFAULT '0' COMMENT '变动的数值',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型0HP,1金币,2水晶',
  `description` varchar(225) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `change_at` datetime NOT NULL COMMENT '变动时间',
  PRIMARY KEY (`id`),
  KEY `fk-wealth_records-user_id` (`user_id`),
  CONSTRAINT `fk-wealth_records-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`uid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS  `replies`;
CREATE TABLE `replies` (
  `replyid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `comment_id` int(11) unsigned DEFAULT NULL COMMENT '回复的评论id',
  `content` text COLLATE utf8_unicode_ci COMMENT '评论内容',
  `user_id` int(11) unsigned DEFAULT '0' COMMENT '外键user表id',
  `reply_to` int(11) unsigned DEFAULT '0' COMMENT '外键回复@会员id',
  `stand` tinyint(1) DEFAULT '0' COMMENT '立场 -1反方，1正方，0中立',
  `os` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '操作系统',
  `brower` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '浏览器',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0正常1不显示',
  `reply_at` datetime NOT NULL COMMENT '回复时间',
  PRIMARY KEY (`replyid`),
  KEY `fk-replies-comment_id` (`comment_id`),
  KEY `fk-replies-user_id` (`user_id`),
  CONSTRAINT `fk-replies-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`uid`) ON DELETE CASCADE,
  CONSTRAINT `fk-replies-comment_id` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`commentid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS  `images`;
CREATE TABLE `images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) unsigned DEFAULT '0' COMMENT '外键user表id',
  `img_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `img_title` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `img_cate` tinyint(1) NOT NULL DEFAULT '0' COMMENT '分类0无，1文章',
  `img_path` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '保存路径',
  `img_size` int(11) unsigned DEFAULT '0' COMMENT '图像大小',
  `img_mime` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '图像MIME',
  `img_suffix` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '后缀名',
  `img_md5` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '图像的md5值',
  `img_sha1` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '图像的sha1值',
  `img_version` int(11) NOT NULL DEFAULT '1' COMMENT '图像版本',
  `img_width` smallint(6) NOT NULL DEFAULT '0' COMMENT '宽度像素',
  `img_height` smallint(6) NOT NULL DEFAULT '1' COMMENT '高度像素',
  `thumb_path` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '缩略图保存路径',
  `thumb_suffix` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '缩略图后缀',
  `img_original` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '原图标识',
  `created_at` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index-images-img_md5` (`user_id`,`img_md5`),
  UNIQUE KEY `index-images-img_sha1` (`user_id`,`img_sha1`),
  KEY `index-images-created_at` (`created_at`),
  CONSTRAINT `fk-images-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`uid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

insert into `images`(`id`,`user_id`,`img_name`,`img_title`,`img_cate`,`img_path`,`img_size`,`img_mime`,`img_suffix`,`img_md5`,`img_sha1`,`img_version`,`img_width`,`img_height`,`thumb_path`,`thumb_suffix`,`img_original`,`created_at`) values
('1','10001','c010dbe67a4474b0855ff2b53eba6d31','1000120170326160553','0','/upload/img/10001/2017-03-26/','262162','image/png','.jpg','55dd5939e1baf408f764f21000736c2f','56a101bde7fc9dd885bfad5f2a8fa3a310dceecf','1','622','388','/upload/thumb/10001/2017-03-26/','.jpg','_original','2017-03-26 16:05:53'),
('2','10001','41a2dd25293aedd90698bbb3229a444f','1000120170326170509','0','/upload/img/10001/2017-03-26/','33306','image/jpeg','.jpg','8675d7197657737d6ae7119d9a7d33fa','3f1b6d5933050ae7b7ddcd42666a17acb596efd5','4','673','298','/upload/thumb/10001/2017-03-26/','.jpg','_original','2017-03-26 17:05:09'),
('3','10001','10897fd334fcf6b86ba60e0070c1e536','1000120170326170734','0','/upload/img/10001/2017-03-26/','108021','image/jpeg','.jpg','5ec91ca33dc0b14812637281edb3e81c','2a76bc37962839dbd44c79c0962668e0130867c8','1','741','419','/upload/thumb/10001/2017-03-26/','.jpg','_original','2017-03-26 17:07:34'),
('4','10001','09f0245f3f0610a8108f5d9008975a4d','1000120170326171157','0','/upload/img/10001/2017-03-26/','37639','image/jpeg','.jpg','cac5874fededd80ee37b5297ad669eaa','226115199035ae106e754752126b11319da095c4','1','815','133','/upload/thumb/10001/2017-03-26/','.jpg','_original','2017-03-26 17:11:57'),
('5','10001','2c5e7cd8f3ce212d704a16776ae62730','1000120170326171820','0','/upload/img/10001/2017-03-26/','120463','image/jpeg','.jpg','1a4eaf3fdd33613fad468ca439262781','ff49c7c6b45f8c63c68f105a7bb02dc09309d31c','1','737','521','/upload/thumb/10001/2017-03-26/','.jpg','_original','2017-03-26 17:18:20'),
('6','10001','c74e8cf309ab16a5abefd3fd3f3f9450','1000120170329213950','0','/upload/img/10001/2017-03-29/','23293','image/jpeg','.jpg','ed1b5ec6b83d177c35ec489683671f00','a9b3c96b3a4ba71697bb152027b58cc7b119cc07','1','619','381','/upload/thumb/10001/2017-03-29/','.jpg','_original','2017-03-29 21:39:50'),
('7','10001','ac43d70899cc5d09f297682a90e643de','1000120170329221753','0','/upload/img/10001/2017-03-29/','117279','image/jpeg','.jpg','4d6abd278d388af1c1279e02530966a1','a194e3c1364429f33c666fdb1de166ff0e97a8ce','3','646','430','/upload/thumb/10001/2017-03-29/','.jpg','_original','2017-03-29 22:17:53');
DROP TABLE IF EXISTS  `term_relations`;
CREATE TABLE `term_relations` (
  `objectid` int(11) unsigned NOT NULL COMMENT '文章id/链接id/菜单id',
  `term` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '外键terms表id',
  `sort` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `type` varchar(4) NOT NULL DEFAULT '' COMMENT '类型post,link,menu',
  PRIMARY KEY (`objectid`,`term`),
  KEY `fk-term_relations-term` (`term`),
  CONSTRAINT `fk-term_relations-term` FOREIGN KEY (`term`) REFERENCES `terms` (`termid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

insert into `term_relations`(`objectid`,`term`,`sort`,`type`) values
('1','2','0','menu'),
('1','4','0','post'),
('2','2','0','menu'),
('2','4','0','post'),
('3','2','0','menu'),
('3','4','0','post'),
('4','1','0','menu'),
('4','12','0','post'),
('4','14','0','post'),
('4','16','0','post'),
('4','17','0','post'),
('5','1','0','menu'),
('5','12','0','post'),
('5','14','0','post'),
('5','18','0','post'),
('5','19','0','post'),
('6','1','0','menu'),
('6','3','0','post'),
('7','1','0','menu'),
('7','12','0','post'),
('7','14','0','post'),
('7','18','0','post'),
('7','19','0','post'),
('8','1','0','menu'),
('8','7','0','post'),
('8','8','0','post'),
('8','20','0','post'),
('9','1','0','menu'),
('10','1','0','menu'),
('11','1','0','menu'),
('12','1','0','menu'),
('13','2','0','menu');
DROP TABLE IF EXISTS  `user`;
CREATE TABLE `user` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `group` smallint(6) unsigned NOT NULL COMMENT '用户组id',
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '会员名',
  `nickname` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '昵称',
  `email` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Email',
  `head` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '头像',
  `pay_qrcode` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '付款二维',
  `mobile` bigint(11) NOT NULL DEFAULT '0' COMMENT '手机',
  `sex` tinyint(1) NOT NULL DEFAULT '-1' COMMENT '性别:1男0女-1保密',
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '认证token',
  `password` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '登录密码',
  `reset_token` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '重置密码token',
  `reset_token_expire` datetime DEFAULT NULL COMMENT '重置密码token失效时间',
  `motto` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '个人签名',
  `hp` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '生命值',
  `golds` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '金币',
  `crystal` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '水晶',
  `posts` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文章量',
  `comments` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '评论量',
  `friends` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关注量',
  `followers` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '粉丝量',
  `os` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '登录所用OS',
  `browser` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '登录所用browser',
  `isauth` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否认证',
  `status` smallint(6) NOT NULL DEFAULT '1' COMMENT '状态:-1已注消0登录锁定1正常10禁止评论20禁止发表',
  `safe_level` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '帐户安全分数',
  `created_at` datetime NOT NULL COMMENT '注册时间',
  `updated_at` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `nickname` (`nickname`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `index-user-uid-mobile` (`uid`,`mobile`),
  KEY `index-user-status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=10003 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

insert into `user`(`uid`,`group`,`username`,`nickname`,`email`,`head`,`pay_qrcode`,`mobile`,`sex`,`auth_key`,`password`,`reset_token`,`reset_token_expire`,`motto`,`hp`,`golds`,`crystal`,`posts`,`comments`,`friends`,`followers`,`os`,`browser`,`isauth`,`status`,`safe_level`,`created_at`,`updated_at`) values
('10000','10','len168','len168','toshcn@qq.com','/public/img/avatar.jpg','https://www.len168.com/upload/img/10000/pay_qrcode/pay.jpg?v=1490506788','0','-1','4Vo04tP2lTigza8e3jG1y1wZLsEkOfRD','$2y$13$TjD/HnjyRmbm7ELqGj8a7.mmNZBNPpbAesQKIkFR/bsqci/xB/xd2','',null,'len168分享技术','0','0','0','3','0','1','0','','','1','1','0','2017-03-25 08:02:11','2017-03-26 13:39:48'),
('10001','10','toshcn','toshcn','toshcn@foxmail.com','https://www.len168.com/upload/img/10001/avatar/9d0df86a541a43551a3f45e62b00edf6.jpg','https://www.len168.com/upload/img/10001/pay_qrcode/pay.jpg?v=1490511021','0','-1','hBS0U9ueb8NYzMwore1x4EHYlAKjk3Pq','$2y$13$TvB.U0FPcoeGfPqnSTaSLOhlMZMR4GBsvefWF7YE6zo7r8z4FdBLO','',null,'一直屌丝，从未放弃','1','2','0','4','1','0','1','','','1','1','0','2017-03-25 08:03:09','2017-03-29 21:26:39'),
('10002','20','xzzhht','xzzhht','904091739@qq.com','https://avatars1.githubusercontent.com/u/19225469?v=3','','0','-1','577G5j5T19xwJ__Us1dGqjBAJgJBftx3','$2y$13$wDp6NE70XqeabcPMTyUEm.DnBkSXFxhR2Rv4xsWE0ypx2sZSt0ifS','',null,'','0','0','0','1','0','0','0','','','0','1','0','2017-03-27 12:44:18','2017-03-27 12:44:18');
DROP TABLE IF EXISTS  `comments`;
CREATE TABLE `comments` (
  `commentid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `post_id` int(11) unsigned DEFAULT '0' COMMENT '外键posts表id',
  `user_id` int(11) unsigned DEFAULT '0' COMMENT '外键user表id',
  `content` text COLLATE utf8_unicode_ci COMMENT '评论内容',
  `replies` int(11) unsigned DEFAULT '0' COMMENT '评论回复数',
  `stand` tinyint(1) DEFAULT '0' COMMENT '立场 -1反方，1正方，0中立',
  `hp` int(11) DEFAULT '100' COMMENT '生命值',
  `isforever` tinyint(1) DEFAULT '0' COMMENT '评论是否免死',
  `isdie` tinyint(1) DEFAULT '0' COMMENT '评论是否死亡',
  `apps` int(11) unsigned DEFAULT '0' COMMENT '正方人数',
  `opps` int(11) unsigned DEFAULT '0' COMMENT '反方人数',
  `neutrals` int(11) unsigned DEFAULT '0' COMMENT '中立人数',
  `os` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '操作系统',
  `brower` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '浏览器',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0正常1不显示',
  `comment_at` datetime NOT NULL COMMENT '评论时间',
  `updated_at` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`commentid`),
  KEY `fk-comments-post_id` (`post_id`),
  KEY `fk-comments-user_id` (`user_id`),
  CONSTRAINT `fk-comments-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`uid`) ON DELETE CASCADE,
  CONSTRAINT `fk-comments-post_id` FOREIGN KEY (`post_id`) REFERENCES `posts` (`postid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

insert into `comments`(`commentid`,`post_id`,`user_id`,`content`,`replies`,`stand`,`hp`,`isforever`,`isdie`,`apps`,`opps`,`neutrals`,`os`,`brower`,`status`,`comment_at`,`updated_at`) values
('1','5','10001','先占个坑，等人来埋','0','1','100','0','0','0','0','0','Windows','','0','2017-03-28 13:02:58','2017-03-28 13:02:58');
DROP TABLE IF EXISTS  `auth`;
CREATE TABLE `auth` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) unsigned DEFAULT NULL COMMENT '外键user表id',
  `source` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '第三平台名称如github',
  `source_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '第三平台open_id',
  `nickname` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '第三平台昵称',
  `created_at` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `index-auth-source` (`source`),
  KEY `index-auth-source_id` (`source_id`),
  KEY `fk-auth-user_id` (`user_id`),
  CONSTRAINT `fk-auth-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`uid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

insert into `auth`(`id`,`user_id`,`source`,`source_id`,`nickname`,`created_at`) values
('1','10000','github','24218291','len168','2017-03-27 12:35:46'),
('2','10002','github','19225469','xzzhht','2017-03-27 12:44:18'),
('3','10001','github','9402564','toshcn','2017-03-29 22:26:40');
SET FOREIGN_KEY_CHECKS = 1;

/* VIEWS */;
DROP VIEW IF EXISTS `post_views`;
CREATE VIEW `post_views` AS select `p`.`postid` AS `postid`,`p`.`user_id` AS `user_id`,`p`.`title` AS `title`,`p`.`content` AS `content`,`p`.`author` AS `author`,`p`.`image` AS `image`,`p`.`image_suffix` AS `image_suffix`,`p`.`description` AS `description`,`p`.`original_url` AS `original_url`,`p`.`content_len` AS `content_len`,`p`.`copyright` AS `copyright`,`p`.`spend` AS `spend`,`p`.`paytype` AS `paytype`,`p`.`posttype` AS `posttype`,`p`.`parent` AS `parent`,`p`.`status` AS `status`,`p`.`islock` AS `islock`,`p`.`iscomment` AS `iscomment`,`p`.`isstick` AS `isstick`,`p`.`isnice` AS `isnice`,`p`.`isopen` AS `isopen`,`p`.`ispay` AS `ispay`,`p`.`isforever` AS `isforever`,`p`.`isdie` AS `isdie`,`p`.`os` AS `os`,`pa`.`hp` AS `hp`,`pa`.`golds` AS `golds`,`pa`.`crystal` AS `crystal`,`pa`.`views` AS `views`,`pa`.`comments` AS `comments`,`pa`.`apps` AS `apps`,`pa`.`opps` AS `opps`,`pa`.`neutrals` AS `neutrals`,`p`.`created_at` AS `created_at`,`p`.`updated_at` AS `updated_at`,`u`.`sex` AS `sex`,`u`.`head` AS `head`,`u`.`pay_qrcode` AS `pay_qrcode`,`u`.`nickname` AS `nickname`,`u`.`motto` AS `motto`,`u`.`updated_at` AS `user_updated_at` from ((`posts` `p` join `post_attributes` `pa`) join `user` `u`) where ((`p`.`postid` = `pa`.`post_id`) and (`p`.`user_id` = `u`.`uid`));

