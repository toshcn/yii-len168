<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

use yii\db\Migration;

/**
 * 支付系统数据迁移类
 */
class m170423_102640_payment_table extends Migration
{
    const TB_POST_PAY = '{{%post_pay}}';//文章支付记录表
    const TB_PAYMENT = '{{%payment}}';//支付方式表
    const TB_RECHARGE = '{{%recharge}}';//充值记录表

    public function safeUp()
    {
        /**
         * 根据数据库类型设定数据库引擎
         * @var if driverName is Mysql set $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB AUTO_INCREMENT=1'
         */
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions      = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB AUTO_INCREMENT=1';
        }

        /**
         * 文章支付记录表
         */
        $this->createTable(self::TB_POST_PAY, [
            'id' => $this->primaryKey()->unsigned()->comment('自增id'),
            'user_id' => $this->integer()->unsigned()->comment('外键user表id'),
            'post_id' => $this->integer()->unsigned()->comment('外键post表id'),
            'type' => $this->boolean()->notNull()->defaultValue(0)->comment('1金币,2水晶'),
            'amount' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('支付的数值'),
            'created_at' => $this->dateTime()->notNull()->comment('支付时间'),
        ], $tableOptions);
        $this->createIndex('idx-user_id-post_id', self::TB_POST_PAY, ['user_id', 'post_id'], true);

        /**
         * 支付方式表
         */
        $this->createTable(self::TB_PAYMENT, [
            'id' => $this->smallInteger()->unsigned()->notNull()->comment('支付方式id'),
            'title' => $this->string(30)->notNull()->defaultValue('')->comment('支付名称'),
            'name' => $this->string(20)->notNull()->defaultValue('')->comment('支付方式代号'),
            'tokenid' => $this->string(128)->notNull()->defaultValue('')->comment('第三方支付id'),
            'tokenkey' => $this->string(128)->notNull()->defaultValue('')->comment('第三方支付密钥'),
            'logo' => $this->string(128)->notNull()->defaultValue(''),
            'qrcode' => $this->string(128)->notNull()->defaultValue('')->comment('收款二维码'),
            'note' => $this->string(128)->notNull()->defaultValue('')->comment('支付说明，前端显示'),
            'status' => $this->boolean()->notNull()->defaultValue(0)->comment('状态'),
            'description' => $this->string(128)->notNull()->defaultValue('')->comment('描述')
        ], $tableOptions);
        $this->createIndex('idx-name', self::TB_PAYMENT, ['name'], true);

        /**
         * 充值记录表
         */
        $this->createTable(self::TB_RECHARGE, [
            'id' => $this->primaryKey()->unsigned()->comment('自增id'),
            'amount' => $this->decimal(8, 2)->unsigned()->defaultValue(0.00)->comment('充值金额'),
            'user_id' => $this->integer()->unsigned()->comment('外键user表id'),
            'payment_id' => $this->smallInteger()->unsigned()->comment('外键payment表id'),
            'payment_title' => $this->string(32)->notNull()->defaultValue('')->comment('支付平台名称'),
            'trade_no' => $this->string(64)->notNull()->defaultValue('')->comment('支付交易号'),
            'trade_account' => $this->string(64)->notNull()->defaultValue('')->comment('支付账号'),
            'created_at' => $this->dateTime()->notNull()->comment('支付时间'),
        ], $tableOptions);
         $this->createIndex('idx-user_id', self::TB_RECHARGE, ['user_id']);
    }

    public function safeDown()
    {
        if (!YII_DEBUG) {
            echo "m170423_102640_payment_table cannot be reverted.\n";
            return false;
        }

        $this->dropTable(self::TB_RECHARGE);
        $this->dropTable(self::TB_PAYMENT);
        $this->dropTable(self::TB_POST_PAY);
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
