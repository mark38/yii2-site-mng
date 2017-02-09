<?php

use yii\db\Migration;

class m161204_141930_broadcast_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('broadcast_layouts', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'layout_path' => $this->string(255),
            'content' => $this->text()
        ], $tableOptions);

        $this->createTable('broadcast', [
            'id' => $this->primaryKey(),
            'broadcast_layouts_id' => $this->integer(),
            'registered_users' => $this->boolean(),
            'destinations' => $this->text(),
            'title' => $this->string(255),
            'h1' => $this->string(255),
            'content' => $this->text(),
            'created_at' => $this->integer()
        ], $tableOptions);

        $this->addForeignKey('fk-broadcast-broadcast_layouts_id', 'broadcast', 'broadcast_layouts_id', 'broadcast_layouts', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('broadcast_files', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'file' => $this->string(255),
            'broadcast_id' => $this->integer()
        ], $tableOptions);

        $this->addForeignKey('fk-broadcast_files-broadcast_id', 'broadcast_files', 'broadcast_id', 'broadcast', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('broadcast_send', [
            'id' => $this->primaryKey(),
            'broadcast_id' => $this->integer(),
            'created_at' => $this->integer(),
            'status' => $this->boolean()->defaultValue(0)
        ], $tableOptions);

        $this->addForeignKey('fk-broadcast_send-broadcast_id', 'broadcast_send', 'broadcast_id', 'broadcast', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('broadcast_address', [
            'id' => $this->primaryKey(),
            'broadcast_send_id' => $this->integer(),
            'user_id' => $this->integer(),
            'fio' => $this->string(255),
            'email' => $this->string(255),
            'status' => $this->boolean()->defaultValue(0)
        ], $tableOptions);

        $this->addForeignKey('fk-broadcast_address-broadcast_send_id', 'broadcast_address', 'broadcast_send_id', 'broadcast_send', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-broadcast_address-user_id', 'broadcast_address', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');

        $this->insert('modules', [
            'name' => 'Email-рассылка',
            'url' => '/broadcast/index',
            'visible' => 1,
            'icon' => 'fa fa-envelope-o',
        ]);
    }

    public function down()
    {
        $this->dropTable('broadcast_address');
        $this->dropTable('broadcast_send');
        $this->dropTable('broadcast_files');
        $this->dropTable('broadcast');
        $this->dropTable('broadcast_layouts');

        $this->delete('modules', ['url' => '/broadcast/index']);
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
