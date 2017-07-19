<?php

use yii\db\Migration;

class m170615_183502_emails extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('emails', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'state' => $this->boolean()->defaultValue(true)
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('emails');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170615_183502_emails cannot be reverted.\n";

        return false;
    }
    */
}
