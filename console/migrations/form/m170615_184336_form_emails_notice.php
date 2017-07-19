<?php

use yii\db\Migration;

class m170615_184336_form_emails_notice extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('form_emails_notice', [
            'id' => $this->primaryKey(),
            'form_types_id' => $this->integer(),
            'emails_id' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('fk-form_emails_notice-form_types_id', 'form_emails_notice', 'form_types_id', 'form_types', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-form_emails_notice-emails_id', 'form_emails_notice', 'emails_id', 'emails', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable('form_emails_notice');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170615_184336_form_emails_notice cannot be reverted.\n";

        return false;
    }
    */
}
