<?php

use yii\db\Migration;

class m170326_182017_form extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('form_types', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'select1_name' => $this->string(255),
        ], $tableOptions);

        $this->createTable('form_select', [
            'id' => $this->primaryKey(),
            'form_types_id' => $this->integer(),
            'name' => $this->string(255),
            'seq' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('fk-form_select-form_types_id', 'form_select', 'form_types_id', 'form_types', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('forms', [
            'id' => $this->primaryKey(),
            'form_types_id' => $this->integer(),
            'user_id' => $this->integer(),
            'sessions_id' => $this->integer(),
            'fio' => $this->string(255),
            'phone' => $this->string(255),
            'email' => $this->string(255),
            'comment' => $this->string(2048),
            'form_select1_id' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('fk-forms-form_types_id', 'forms', 'form_types_id', 'form_types', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-forms-user_id', 'forms', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-forms-sessions_id', 'forms', 'sessions_id', 'sessions', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-forms-form_select1_id', 'forms', 'form_select1_id', 'form_select', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('forms');
        $this->dropTable('form_select');
        $this->dropTable('form_types');
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
