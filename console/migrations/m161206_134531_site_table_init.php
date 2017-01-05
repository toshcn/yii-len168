<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license
 */


use yii\db\Schema;
use yii\db\Migration;

/**
 * mysql表结构
 *
 * @author  toshcn <toshcn@foxmail.com>
 * @version  0.1.0
 */
class m161206_134531_site_table_init extends Migration
{
    const TB_USER                   = '{{%user}}';             //会员表数据结构
    const TB_USER_INFO              = '{{%user_info}}';         //会员资料表数据结构
    const TB_USER_LOGIN             = '{{%user_login}}';        //登录表数据结构
    const TB_AUTH                   = '{{%auth}}';             //第三方登录表数据结构
    const TB_FOLLOWERS              = '{{%followers}}';          //会员关注表数据结构
    const TB_WEALTH_RECORDS         = '{{%wealth_records}}';     //财富变动记录表数据结构
    const TB_TERMS                  = '{{%terms}}';             //分类名称表数据结构
    const TB_TERM_RELATIONS         = '{{%term_relations}}';    //分类名称关系表数据结构
    const TB_MENUS                  = '{{%menus}}';             //菜单表数据结构
    const TB_LINKS                  = '{{%links}}';             //链接表数据结构
    const TB_POSTS                  = '{{%posts}}';             //文章表数据结构
    const TB_POST_ATTRIBUTES        = '{{%post_attributes}}';  //文章属性表数据结构
    const TB_IMAGES                 = '{{%images}}';            //图片表数据结构
    const TB_COMMENTS               = '{{%comments}}';          //评论表数据结构
    const TB_REPLIES                = '{{%replies}}';           //评论回复表数据结构
    const TB_POST_REPORTS           = '{{%post_reports}}';      //文章举报表数据结构
    const TB_COMMENT_REPORTS        = '{{%comment_reports}}';  //评论举报表数据结构
    const TB_REPLY_REPORTS          = '{{%reply_reports}}';    //回复举报表数据结构
    const TB_COMMONS                = '{{%commons}}';           //公告表数据结构
    const TB_INVITES                = '{{%invites}}';           //邀请码表数据结构
    const TB_MESSAGES               = '{{%messages}}';          //短信息表数据结构
    const TB_NOTICES                = '{{%notices}}';            //通知表数据结构

    const TB_POST_VIEWS = '{{%post_views}}';//文章视图表

    public function safeUp()
    {
        $tableOptions      = null;
        $tableOptions_user = null;

        /**
         * 根据数据库类型设定数据库引擎
         * @var if driverName is Mysql set $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB AUTO_INCREMENT=1'
         */
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions      = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB AUTO_INCREMENT=1';
            $tableOptions_user = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB AUTO_INCREMENT=10000';
        }

        //会员表
        $this->createTable(self::TB_USER, [
            'uid' => $this->primaryKey()->unsigned()->comment('自增id'),
            'group' => $this->smallInteger()->unsigned()->notNull()->comment('用户组id'),
            'username' => $this->string(20)->notNull()->unique()->comment('会员名'),
            'nickname' => $this->string(20)->notNull()->unique()->comment('昵称'),
            'email' => $this->string(64)->notNull()->unique()->comment('Email'),
            'head' => $this->string(255)->notNull()->defaultValue('')->comment('头像'),
            'mobile' => $this->bigInteger(11)->notNull()->defaultValue(0)->comment('手机'),
            'sex' => $this->boolean()->notNull()->defaultValue(-1)->comment('性别:1男0女-1保密'),
            'auth_key' => $this->string(32)->notNull()->defaultValue('')->comment('认证token'),
            'password' => $this->string(128)->notNull()->defaultValue('')->comment('登录密码'),
            'reset_token' => $this->string(64)->notNull()->defaultValue('')->comment('重置密码token'),
            'reset_token_expire' => $this->dateTime()->comment('重置密码token失效时间'),
            'motto' => $this->string(32)->notNull()->defaultValue('')->comment('个人签名'),

            'hp' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('生命值'),
            'golds' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('金币'),
            'crystal' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('水晶'),
            'posts' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('文章量'),
            'comments' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('评论量'),
            'friends' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('关注量'),
            'followers' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('粉丝量'),

            'os' => $this->string(20)->notNull()->defaultValue('')->comment('登录所用OS'),
            'browser' => $this->string(20)->notNull()->defaultValue('')->comment('登录所用browser'),

            'isauth' => $this->boolean()->notNull()->defaultValue(0)->comment('是否认证'),
            'status' => $this->smallInteger()->notNull()->defaultValue(1)->comment('状态:-1已注消0登录锁定1正常10禁止评论20禁止发表'),
            'safe_level' => $this->smallInteger()->unsigned()->notNull()->defaultValue(0)->comment('帐户安全分数'),
            'created_at' => $this->dateTime()->notNull()->comment('注册时间'),
            'updated_at' => $this->dateTime()->comment('更新时间'),
        ], $tableOptions_user);
        $this->createIndex('index-user-uid-mobile', self::TB_USER, ['uid', 'mobile'], true);
        $this->createIndex('index-user-status', self::TB_USER, ['status']);


        //会员信息表
        $this->createTable(self::TB_USER_INFO, [
            'id' => $this->primaryKey()->unsigned()->comment('自增id'),
            'user_id' => $this->integer()->unsigned()->unique()->comment('外键user表id'),
            'realname' => $this->string(15)->notNull()->defaultValue('')->comment('真名'),
            'mobile' => $this->bigInteger()->notNull()->defaultValue(0)->comment('手机'),
            'birthday' => $this->date()->notNull()->comment('生日'),
            'country' => $this->smallInteger()->unsigned()->defaultValue(86)->comment('国家代码'),
            'province' => $this->integer()->unsigned()->defaultValue(0)->comment('省份代码'),
            'city' => $this->integer()->unsigned()->defaultValue(0)->comment('城市代码'),
            'address' => $this->string(64)->defaultValue('')->comment('详细地址'),
            'idcode' => $this->string(24)->defaultValue('')->comment('身份证'),
            'created_at' => $this->dateTime()->notNull()->comment('添加时间'),
            'updated_at' => $this->dateTime()->comment('更新时间'),

        ], $tableOptions);

        //会员登录表
        $this->createTable(self::TB_USER_LOGIN, [
            'id' => $this->primaryKey()->unsigned()->comment('自增id'),
            'user_id' => $this->integer()->unsigned()->unique()->comment('外键user表id'),
            'counts' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('登录次数'),
            'failes' => $this->smallInteger()->unsigned()->notNull()->defaultValue(0)->comment('登录失败次数'),
            'interval' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('登录间隔秒'),
            'ipv4' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('登录IP'),
            'last_ipv4' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('最近登录IP'),
            'failed_at' => $this->dateTime()->comment('登录错误时间'),
            'login_at' => $this->dateTime()->notNull()->comment('登录时间'),
        ], $tableOptions);

        //第三方认证表
        $this->createTable(self::TB_AUTH, [
            'id' => $this->primaryKey()->unsigned()->comment('自增id'),
            'user_id' => $this->integer()->unsigned()->comment('外键user表id'),
            'source' => $this->string(15)->notNull()->defaultValue('')->comment('第三平台名称如github'),
            'source_id' => $this->string(64)->notNull()->defaultValue('')->comment('第三平台open_id'),
            'nickname' => $this->string(64)->notNull()->defaultValue('')->comment('第三平台昵称'),
            'created_at' => $this->dateTime()->notNull()->comment('添加时间'),
        ], $tableOptions);
        $this->createIndex('index-auth-source', self::TB_AUTH, ['source']);
        $this->createIndex('index-auth-source_id', self::TB_AUTH, ['source_id']);


        //关注表
        $this->createTable(self::TB_FOLLOWERS, [
            'id' => $this->primaryKey()->unsigned()->comment('自增id'),
            'user_id' => $this->integer()->unsigned()->comment('外键关注user_id'),
            'follower_id' => $this->integer()->unsigned()->comment('外键粉丝user_id'),
            'status' => $this->boolean()->notNull()->defaultValue(0)->comment('关注状态1关注,0取消'),
            'created_at' => $this->dateTime()->notNull()->comment('关注时间'),
            'updated_at' => $this->dateTime()->comment('更新时间'),
        ]);


        //财富变动表数据结构
        $this->createTable(self::TB_WEALTH_RECORDS, [
            'id' => $this->primaryKey()->unsigned()->comment('自增id'),
            'user_id' => $this->integer()->unsigned()->comment('外键user表id'),
            'value' => $this->integer()->notNull()->defaultValue(0)->comment('变动的数值'),
            'type' => $this->boolean()->notNull()->defaultValue(0)->comment('类型0HP,1金币,2水晶'),
            'description' => $this->string(225)->notNull()->defaultValue('')->comment('描述'),
            'change_at' => $this->dateTime()->notNull()->comment('变动时间'),
        ], $tableOptions);

        /**
         * terms 名称表数据结构
         */
        $this->createTable(self::TB_TERMS, [
            'termid' => $this->primaryKey()->unsigned()->comment('自增id'),
            'title' => $this->string(32)->notNull()->defaultValue('')->comment('名称标题'),
            'slug' => $this->string(32)->notNull()->defaultValue('')->comment('名称英文标题'),
            'catetype' => $this->string(15)->notNull()->defaultValue('')->comment('分类category nav_menu post_tag link_category'),
            'description' => $this->string(64)->notNull()->defaultValue('')->comment('描述'),
            'parent' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('父级termid'),
            'counts' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('分类文章数'),
        ], $tableOptions);
        $this->createIndex('index-terms-parent', self::TB_TERMS, ['parent']);
        $this->createIndex('index-terms-counts', self::TB_TERMS, ['counts']);

        /**
         * menu菜单表数据结构
         * object关联的id由menu_type指定的类型对应为termid, postid, linkid
         */
        $this->createTable(self::TB_MENUS, [
            'menuid'  => $this->primaryKey()->unsigned()->comment('自增id'),
            'object' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('关联的id'),
            'menu_title'  => $this->string(64)->notNull()->defaultValue('')->comment('菜单名称'),
            'menu_type' => $this->string(4)->notNull()->defaultValue('')->comment('菜单类型term, post, link'),
            'menu_parent' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('父菜单'),
            'menu_sort'   => $this->smallInteger()->unsigned()->notNull()->defaultValue(0)->comment('排序'),
        ], $tableOptions);
        /**
         * link链接表数据结构
         */
        $this->createTable(self::TB_LINKS, [
            'linkid'  => $this->primaryKey()->unsigned()->comment('自增id'),
            'link_title'  => $this->string(64)->notNull()->defaultValue('')->comment('链接名称'),
            'link_type' => $this->string(8)->notNull()->defaultValue('')->comment('链接类型local, friendly'),
            'link_url' => $this->string(255)->notNull()->defaultValue('')->comment('URL地址'),
            'link_icon' => $this->string(255)->notNull()->defaultValue('')->comment('链接图标'),
            'link_sort'   => $this->smallInteger()->unsigned()->notNull()->defaultValue(0)->comment('排序'),
        ], $tableOptions);

        /**
         * 名称和分类对应关系表数据结构
         */
        $this->createTable(self::TB_TERM_RELATIONS, [
            'objectid' => $this->integer()->unsigned()->notNull()->comment('文章id/链接id/菜单id'),
            'term'   => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('外键terms表id'),
            'sort' => $this->smallInteger()->unsigned()->notNull()->defaultValue(0)->comment('排序'),
            'type' => $this->string(4)->notNull()->defaultValue('')->comment('类型post,link,menu'),
        ]);
        $this->addPrimaryKey('pk_objectid_termid', self::TB_TERM_RELATIONS, ['objectid', 'term']);

        /**
         * post文章表数据结构
         */
        $this->createTable(self::TB_POSTS, [
            'postid'      => $this->primaryKey()->unsigned()->comment('自增id'),
            'user_id' => $this->integer()->unsigned()->comment('外键user表id'),
            'title' => $this->string(60)->notNull()->defaultValue('')->comment('文章标题'),
            'author' => $this->string(15)->notNull()->defaultValue('')->comment('文章作者'),
            'categorys' => $this->string(32)->notNull()->defaultValue('')->comment('文章分类id列'),
            'image' => $this->string(128)->notNull()->defaultValue('')->comment('封面图'),
            'image_suffix' => $this->string(20)->notNull()->defaultValue('')->comment('封面图后缀'),
            'content' => $this->text()->comment('文章内容'),
            'content_len' => $this->integer()->notNull()->defaultValue(0)->comment('内容长度'),
            'description' => $this->string(255)->notNull()->defaultValue('')->comment('文章描述'),
            'original_url' => $this->string(255)->notNull()->defaultValue('')->comment('转载文章原链接'),
            'copyright'   => $this->boolean()->notNull()->defaultValue(0)->comment('文章版权声明 0转载注明出处，1转载联系作者，2禁止转载'),
            'spend' => $this->smallInteger()->notNull()->defaultValue(0)->comment('阅读花费'),
            'paytype' => $this->boolean()->notNull()->defaultValue(0)->comment('花费类型1金币2水晶'),
            'posttype' => $this->boolean()->notNull()->defaultValue(1)->comment('文章类型，1原创，2转载，3翻译'),
            'parent' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('文章父id'),
            'status' => $this->smallInteger()->notNull()->defaultValue(0)->comment('回收站-1,草稿0,发表1'),
            'islock' => $this->boolean()->notNull()->defaultValue(0)->comment('1锁定（无法阅读和评论），0否'),
            'iscomment' => $this->boolean()->notNull()->defaultValue(0)->comment('是否开放评论'),
            'isstick' => $this->boolean()->notNull()->defaultValue(0)->comment('是否置顶'),
            'isnice' => $this->boolean()->notNull()->defaultValue(0)->comment('是否推荐'),
            'isopen' => $this->boolean()->notNull()->defaultValue(0)->comment('是否公开'),
            'ispay' => $this->boolean()->notNull()->defaultValue(0)->comment('是否需要付费'),
            'isforever' => $this->boolean()->notNull()->defaultValue(1)->comment('是否免死'),
            'isdie' => $this->boolean()->notNull()->defaultValue(0)->comment('文章死亡'),
            'os'  => $this->string(20)->notNull()->defaultValue('')->comment('操作系统'),
            'browser'  => $this->string(20)->notNull()->defaultValue('')->comment('浏览器'),
            'created_at' => $this->dateTime()->notNull()->comment('添加时间'),
            'updated_at' => $this->dateTime()->comment('更新时间'),
        ], $tableOptions);
        $this->createIndex('index-posts-user_id-title', self::TB_POSTS, ['user_id', 'title'], true);
        $this->createIndex('index-posts-author', self::TB_POSTS, 'author');
        $this->createIndex('index-posts-status', self::TB_POSTS, 'status');


        /**
         * 文章属性表数据结构
         */
        $this->createTable(self::TB_POST_ATTRIBUTES, [
            'id'  => $this->primaryKey()->unsigned()->comment('自增id'),
            'post_id' => $this->integer()->unsigned()->comment('外键posts表id'),
            'hp'  => $this->integer()->notNull()->defaultValue(100)->comment('文章生命值'),
            'golds' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('金币'),
            'crystal' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('水晶'),
            'views' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('浏览量'),
            'comments' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('评论量'),
            'apps' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('正方人数'),
            'opps' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('反方人数'),
            'neutrals' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('中立人数'),
        ], $tableOptions);
        $this->createIndex('index-posts-views', self::TB_POST_ATTRIBUTES, 'views');
        $this->createIndex('index-posts-comments', self::TB_POST_ATTRIBUTES, 'comments');


        /**
         * images图片表数据结构
         */
        $this->createTable(self::TB_IMAGES, [
            'id' => $this->primaryKey()->unsigned()->comment('自增id'),
            'user_id' => $this->integer()->unsigned()->defaultValue(0)->comment('外键user表id'),
            'img_name' => $this->string(32)->notNull()->defaultValue('')->comment('名称'),
            'img_title' => $this->string(64)->notNull()->defaultValue('')->comment('标题'),
            'img_cate' => $this->boolean()->notNull()->defaultValue(0)->comment('分类0无，1文章'),
            'img_path' => $this->string(128)->notNull()->defaultValue('')->comment('保存路径'),
            'img_size' => $this->integer()->unsigned()->defaultValue(0)->comment('图像大小'),
            'img_mime' => $this->string(20)->notNull()->defaultValue('')->comment('图像MIME'),
            'img_suffix' => $this->string(10)->notNull()->defaultValue('')->comment('后缀名'),
            'img_md5' => $this->string(32)->notNull()->defaultValue('')->comment('图像的md5值'),
            'img_sha1' => $this->string(40)->notNull()->defaultValue('')->comment('图像的sha1值'),
            'img_version' => $this->integer()->notNull()->defaultValue(1)->comment('图像版本'),
            'img_width' => $this->smallInteger()->notNull()->defaultValue(0)->comment('宽度像素'),
            'img_height' => $this->smallInteger()->notNull()->defaultValue(1)->comment('高度像素'),
            'thumb_path' => $this->string(128)->notNull()->defaultValue('')->comment('缩略图保存路径'),
            'thumb_suffix' => $this->string(10)->notNull()->defaultValue('')->comment('缩略图后缀'),
            'img_original' => $this->string(10)->notNull()->defaultValue('')->comment('原图标识'),
            'created_at' => $this->dateTime()->notNull()->comment('添加时间'),
        ], $tableOptions);
        $this->createIndex('index-images-img_md5', self::TB_IMAGES, ['user_id', 'img_md5'], true);
        $this->createIndex('index-images-img_sha1', self::TB_IMAGES, ['user_id', 'img_sha1'], true);
        $this->createIndex('index-images-created_at', self::TB_IMAGES, 'created_at');


        /**
         * comment评论表数据结构
         */
        $this->createTable(self::TB_COMMENTS, [
            'commentid'  => $this->primaryKey()->unsigned()->comment('自增id'),
            'post_id' => $this->integer()->unsigned()->defaultValue(0)->comment('外键posts表id'),
            'user_id' => $this->integer()->unsigned()->defaultValue(0)->comment('外键user表id'),
            'content' => $this->text()->comment('评论内容'),
            'replies' => $this->integer()->unsigned()->defaultValue(0)->comment('评论回复数'),
            'stand' => $this->boolean()->defaultValue(0)->comment('立场 -1反方，1正方，0中立'),
            'hp' => $this->integer()->defaultValue(100)->comment('生命值'),
            'isforever'=> $this->boolean()->defaultValue(0)->comment('评论是否免死'),
            'isdie' => $this->boolean()->defaultValue(0)->comment('评论是否死亡'),
            'apps' => $this->integer()->unsigned()->defaultValue(0)->comment('正方人数'),
            'opps' => $this->integer()->unsigned()->defaultValue(0)->comment('反方人数'),
            'neutrals' => $this->integer()->unsigned()->defaultValue(0)->comment('中立人数'),
            'os'  => $this->string(20)->notNull()->defaultValue('')->comment('操作系统'),
            'brower'  => $this->string(20)->notNull()->defaultValue('')->comment('浏览器'),
            'comment_at' => $this->dateTime()->notNull()->comment('评论时间'),
            'updated_at' => $this->dateTime()->comment('更新时间'),
        ], $tableOptions);

        /**
         * reply评论回复表数据结构
         */
        $this->createTable(self::TB_REPLIES, [
            'replyid'  => $this->primaryKey()->unsigned()->comment('自增id'),
            'comment_id'  => $this->integer()->unsigned()->comment('回复的评论id'),
            'content'  => $this->text()->comment('评论内容'),
            'user_id' => $this->integer()->unsigned()->defaultValue(0)->comment('外键user表id'),
            'reply_to' => $this->integer()->unsigned()->defaultValue(0)->comment('外键回复@会员id'),
            'stand' => $this->boolean()->defaultValue(0)->comment('立场 -1反方，1正方，0中立'),
            'os'  => $this->string(20)->notNull()->defaultValue('')->comment('操作系统'),
            'brower'  => $this->string(20)->notNull()->defaultValue('')->comment('浏览器'),
            'reply_at' => $this->dateTime()->notNull()->comment('回复时间'),
        ], $tableOptions);


        /**
         * post_reports文章举报表
         */
        $this->createTable(self::TB_POST_REPORTS, [
            'id' => $this->primaryKey()->unsigned()->comment('自增id'),
            'post_id' => $this->integer()->unsigned()->comment('外键posts表id'),
            'tipster_id' => $this->integer()->unsigned()->comment('外键举报者id'),
            'type' => $this->smallInteger()->unsigned()->defaultValue(0)->comment('举报的类型'),
            'description' => $this->string(255)->notNull()->defaultValue('')->comment('额外描述'),
            'created_at' => $this->dateTime()->notNull()->comment('添加时间'),
        ], $tableOptions);


        /**
         * comment_reports评论举报表
         */
        $this->createTable(self::TB_COMMENT_REPORTS, [
            'id' => $this->primaryKey()->unsigned()->comment('自增id'),
            'comment_id' => $this->integer()->unsigned()->comment('外键comments表id'),
            'tipster_id' => $this->integer()->unsigned()->comment('外键举报者id'),
            'type' => $this->smallInteger()->unsigned()->defaultValue(0)->comment('举报的类型'),
            'description' => $this->string(255)->notNull()->defaultValue('')->comment('额外描述'),
            'created_at' => $this->dateTime()->notNull()->comment('添加时间'),
        ], $tableOptions);

        /**
         * reply_reports回复举报表
         */
        $this->createTable(self::TB_REPLY_REPORTS, [
            'id' => $this->primaryKey()->unsigned()->comment('自增id'),
            'reply_id' => $this->integer()->unsigned()->comment('外键replies表id'),
            'tipster_id' => $this->integer()->unsigned()->comment('外键举报者id'),
            'type' => $this->smallInteger()->unsigned()->defaultValue(0)->comment('举报的类型'),
            'description' => $this->string(255)->notNull()->defaultValue('')->comment('额外描述'),
            'created_at' => $this->dateTime()->notNull()->comment('添加时间'),
        ], $tableOptions);
        /**
         * commons公告表数据结构
         */
        $this->createTable(self::TB_COMMONS, [
            'id'         => $this->primaryKey()->unsigned()->comment('自增id'),
            'title'      => $this->string(64)->notNull()->defaultValue('')->comment('公告标题'),
            'content'    => $this->text()->notNull()->comment('公告内容'),
            'isshow'     => $this->boolean()->defaultValue(0)->comment('是否显示'),
            'created_at' => $this->dateTime()->notNull()->comment('发布时间'),
        ], $tableOptions);

        /**
         * invitate邀请码表数据结构
         */
        $this->createTable(self::TB_INVITES, [
            'id'       => $this->primaryKey()->unsigned()->comment('自增id'),
            'user_id'  => $this->integer()->unsigned()->comment('外键user表id'),
            'email'    => $this->string(64)->notNull()->unique()->comment('邀请的邮箱'),
            'code'     => $this->string(32)->notNull()->unique()->comment('邀请码'),
            'isuse'    => $this->boolean()->notNull()->defaultValue(0)->comment('是否已使用'),
            'send_at'  => $this->dateTime()->notNull()->comment('发送邀请时间')
        ], $tableOptions);

        /**
         * message短信息表数据结构
         */
        $this->createTable(self::TB_MESSAGES, [
            'id'       => $this->primaryKey()->unsigned()->comment('自增id'),
            'sendfrom' => $this->integer()->unsigned()->comment('外键发信人id'),
            'sendto'   => $this->integer()->unsigned()->comment('外键收信人id'),
            'content'  => $this->string(255)->notNull()->defaultValue('')->comment('内容'),
            'isread'   => $this->boolean()->notNull()->defaultValue(0)->comment('是否阅读'),
            'isforever' => $this->boolean()->notNull()->defaultValue(0)->comment('是否永久保存'),
            'send_at'  => $this->dateTime()->notNull()->comment('发送时间')
        ], $tableOptions);

        /**
         * notice系统通知
         */
        $this->createTable(self::TB_NOTICES, [
            'id' => $this->primaryKey()->unsigned()->comment('自增id'),
            'user_id'  => $this->integer()->unsigned()->comment('被通知会员帐号'),
            'title'     => $this->string(64)->notNull()->defaultValue('')->comment('通知标题'),
            'content'   => $this->string(255)->notNull()->defaultValue('')->comment('通知内容'),
            'isread'    => $this->boolean()->defaultValue(0)->comment('通知是否已阅读'),
            'notice_at' => $this->dateTime()->notNull()->comment('通知时间')
        ], $tableOptions);

        //添加外键约束
        $this->addForeignKey('fk-user_login-user_id', self::TB_USER_LOGIN, 'user_id', self::TB_USER, 'uid', 'CASCADE');
        $this->addForeignKey('fk-user_info-user_id', self::TB_USER_INFO, 'user_id', self::TB_USER, 'uid', 'CASCADE');

        $this->addForeignKey('fk-auth-user_id', self::TB_AUTH, 'user_id', self::TB_USER, 'uid', 'CASCADE');

        $this->addForeignKey('fk-followers-user_id', self::TB_FOLLOWERS, 'user_id', self::TB_USER, 'uid', 'CASCADE');
        $this->addForeignKey('fk-followers-follower_id', self::TB_FOLLOWERS, 'follower_id', self::TB_USER, 'uid', 'CASCADE');

        $this->addForeignKey('fk-wealth_records-user_id', self::TB_WEALTH_RECORDS, 'user_id', self::TB_USER, 'uid', 'CASCADE');

        $this->addForeignKey('fk-term_relations-term', self::TB_TERM_RELATIONS, 'term', self::TB_TERMS, 'termid', 'CASCADE');


        $this->addForeignKey('fk-posts-user_id', self::TB_POSTS, 'user_id', self::TB_USER, 'uid', 'CASCADE');

        $this->addForeignKey('fk-post_attributes-post_id', self::TB_POST_ATTRIBUTES, 'post_id', self::TB_POSTS, 'postid', 'CASCADE');


        $this->addForeignKey('fk-images-user_id', self::TB_IMAGES, 'user_id', self::TB_USER, 'uid', 'CASCADE');

        $this->addForeignKey('fk-comments-post_id', self::TB_COMMENTS, 'post_id', self::TB_POSTS, 'postid', 'CASCADE');
        $this->addForeignKey('fk-comments-user_id', self::TB_COMMENTS, 'user_id', self::TB_USER, 'uid', 'CASCADE');

        $this->addForeignKey('fk-replies-comment_id', self::TB_REPLIES, 'comment_id', self::TB_COMMENTS, 'commentid', 'CASCADE');
        $this->addForeignKey('fk-replies-user_id', self::TB_REPLIES, 'user_id', self::TB_USER, 'uid', 'CASCADE');

        $this->addForeignKey('fk-post_reports-post_id', self::TB_POST_REPORTS, 'post_id', self::TB_POSTS, 'postid', 'CASCADE');
        $this->addForeignKey('fk-post_replies-tipster_id', self::TB_POST_REPORTS, 'tipster_id', self::TB_USER, 'uid', 'CASCADE');

        $this->addForeignKey('fk-comment_reports-comment_id', self::TB_COMMENT_REPORTS, 'comment_id', self::TB_COMMENTS, 'commentid', 'CASCADE');
        $this->addForeignKey('fk-comment_reports-tipster_id', self::TB_COMMENT_REPORTS, 'tipster_id', self::TB_USER, 'uid', 'CASCADE');

        $this->addForeignKey('fk-reply_reports-reply_id', self::TB_REPLY_REPORTS, 'reply_id', self::TB_REPLIES, 'replyid', 'CASCADE');
        $this->addForeignKey('fk-reply_reports-tipster_id', self::TB_REPLY_REPORTS, 'tipster_id', self::TB_USER, 'uid', 'CASCADE');

        $this->addForeignKey('fk-invites-user_id', self::TB_INVITES, 'user_id', self::TB_USER, 'uid', 'CASCADE');

        $this->addForeignKey('fk-messages-sendfrom', self::TB_MESSAGES, 'sendfrom', self::TB_USER, 'uid', 'CASCADE');
        $this->addForeignKey('fk-messages-sendto', self::TB_MESSAGES, 'sendto', self::TB_USER, 'uid', 'CASCADE');


        $this->addForeignKey('fk-notices-user_id', self::TB_NOTICES, 'user_id', self::TB_USER, 'uid', 'CASCADE');

        $this->menuUp();//初始化菜单
        $this->createPostViewTable();//创建文章视图表
    }

    //创建文章视图表
    public function createPostViewTable()
    {
        $sql = 'CREATE
                ALGORITHM = UNDEFINED
                DEFINER = `root`@`localhost`
                SQL SECURITY DEFINER
                VIEW ' . self::TB_POST_VIEWS . ' AS
                    SELECT
                        `p`.`postid` AS `postid`,
                        `p`.`user_id` AS `user_id`,
                        `p`.`title` AS `title`,
                        `p`.`content` AS `content`,
                        `p`.`author` AS `author`,
                        `p`.`image` AS `image`,
                        `p`.`image_suffix` AS `image_suffix`,
                        `p`.`description` AS `description`,
                        `p`.`original_url` AS `original_url`,
                        `p`.`content_len` AS `content_len`,
                        `p`.`copyright` AS `copyright`,
                        `p`.`spend` AS `spend`,
                        `p`.`paytype` AS `paytype`,
                        `p`.`posttype` AS `posttype`,
                        `p`.`parent` AS `parent`,
                        `p`.`status` AS `status`,
                        `p`.`islock` AS `islock`,
                        `p`.`iscomment` AS `iscomment`,
                        `p`.`isstick` AS `isstick`,
                        `p`.`isnice` AS `isnice`,
                        `p`.`isopen` AS `isopen`,
                        `p`.`ispay` AS `ispay`,
                        `p`.`isforever` AS `isforever`,
                        `p`.`isdie` AS `isdie`,
                        `p`.`os` AS `os`,
                        `pa`.`hp` AS `hp`,
                        `pa`.`golds` AS `golds`,
                        `pa`.`crystal` AS `crystal`,
                        `pa`.`views` AS `views`,
                        `pa`.`comments` AS `comments`,
                        `pa`.`apps` AS `apps`,
                        `pa`.`opps` AS `opps`,
                        `pa`.`neutrals` AS `neutrals`,
                        `p`.`created_at` AS `created_at`,
                        `p`.`updated_at` AS `updated_at`,
                        `u`.`sex` AS `sex`,
                        `u`.`head` AS `head`,
                        `u`.`nickname` AS `nickname`,
                        `u`.`motto` AS `motto`
                    FROM
                        ((' .self::TB_POSTS.' `p`
                        JOIN ' .self::TB_POST_ATTRIBUTES. ' `pa`)
                        JOIN ' .self::TB_USER. ' `u`)
                    WHERE
                        ((`p`.`postid` = `pa`.`post_id`)
                            AND (`p`.`user_id` = `u`.`uid`))';
        $this->execute($sql);
    }

    public function menuUp()
    {
        $this->insert(self::TB_TERMS, [
            'title' => '顶部导航',
            'slug' => '顶部导航',
            'catetype' => 'nav_menu'
        ]);
        $this->insert(self::TB_TERMS, [
            'title' => '底部导航',
            'slug' => '底部导航',
            'catetype' => 'nav_menu'
        ]);
        $this->insert(self::TB_TERMS, [
            'title' => '未分类',
            'slug' => '未分类',
            'catetype' => 'category'
        ]);
    }

    public function menuDown()
    {
        $this->delete(self::TB_TERMS, [
            'title' => '顶部导航',
            'catetype' => 'nav_menu'
        ]);
        $this->delete(self::TB_TERMS, [
            'title' => '底部导航',
            'catetype' => 'nav_menu'
        ]);
        $this->delete(self::TB_TERMS, [
            'title' => '未分类',
            'catetype' => 'category'
        ]);
    }


    public function safeDown()
    {
        echo "m161206_134531_site_table_init cannot be reverted.\n";
        $this->menuDown();
        //删除外键约束
        $this->dropForeignKey('fk-user_login-user_id', self::TB_USER_LOGIN);
        $this->dropForeignKey('fk-user_info-user_id', self::TB_USER_INFO);
        $this->dropForeignKey('fk-auth-user_id', self::TB_AUTH);

        $this->dropForeignKey('fk-followers-user_id', self::TB_FOLLOWERS);
        $this->dropForeignKey('fk-followers-follower_id', self::TB_FOLLOWERS);

        $this->dropForeignKey('fk-wealth_records-user_id', self::TB_WEALTH_RECORDS);

        $this->dropForeignKey('fk-term_relations-term', self::TB_TERM_RELATIONS);


        $this->dropForeignKey('fk-posts-user_id', self::TB_POSTS);


        $this->dropForeignKey('fk-images-user_id', self::TB_IMAGES);

        $this->dropForeignKey('fk-comments-post_id', self::TB_COMMENTS);
        $this->dropForeignKey('fk-comments-user_id', self::TB_COMMENTS);

        $this->dropForeignKey('fk-replies-comment_id', self::TB_REPLIES);
        $this->dropForeignKey('fk-replies-user_id', self::TB_REPLIES);

        $this->dropForeignKey('fk-post_reports-post_id', self::TB_POST_REPORTS);
        $this->dropForeignKey('fk-post_replies-tipster_id', self::TB_POST_REPORTS);

        $this->dropForeignKey('fk-comment_reports-comment_id', self::TB_COMMENT_REPORTS);
        $this->dropForeignKey('fk-comment_reports-tipster_id', self::TB_COMMENT_REPORTS);

        $this->dropForeignKey('fk-reply_reports-reply_id', self::TB_REPLY_REPORTS);
        $this->dropForeignKey('fk-reply_reports-tipster_id', self::TB_REPLY_REPORTS);

        $this->dropForeignKey('fk-invites-user_id', self::TB_INVITES);

        $this->dropForeignKey('fk-messages-sendfrom', self::TB_MESSAGES);
        $this->dropForeignKey('fk-messages-sendto', self::TB_MESSAGES);

        $this->dropForeignKey('fk-notices-user_id', self::TB_NOTICES);

        //删除视图
        $this->execute('DROP VIEW '. self::TB_POST_VIEWS);
        //删除表
        $this->dropTable(self::TB_NOTICES);

        $this->dropTable(self::TB_MESSAGES);

        $this->dropTable(self::TB_INVITES);

        $this->dropTable(self::TB_COMMONS);

        $this->dropTable(self::TB_REPLY_REPORTS);

        $this->dropTable(self::TB_COMMENT_REPORTS);

        $this->dropTable(self::TB_POST_REPORTS);

        $this->dropTable(self::TB_POST_ATTRIBUTES);

        $this->dropTable(self::TB_REPLIES);

        $this->dropTable(self::TB_COMMENTS);

        $this->dropTable(self::TB_POSTS);

        $this->dropTable(self::TB_IMAGES);

        $this->dropTable(self::TB_LINKS);

        $this->dropTable(self::TB_TERM_RELATIONS);

        $this->dropTable(self::TB_MENUS);

        $this->dropTable(self::TB_TERMS);

        $this->dropTable(self::TB_WEALTH_RECORDS);

        $this->dropTable(self::TB_FOLLOWERS);

        $this->dropTable(self::TB_AUTH);

        $this->dropTable(self::TB_USER_LOGIN);

        $this->dropTable(self::TB_USER_INFO);

        $this->dropTable(self::TB_USER);
    }
}
