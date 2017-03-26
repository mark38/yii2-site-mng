<?php

use yii\db\Migration;

class m170324_215042_items extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('item_types', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'gallery_types_id' => $this->integer(),
            'gallery_groups_id' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('fk-item_types-gallery_types_id', 'item_types', 'gallery_types_id', 'gallery_types', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-item_types-gallery_groups_id', 'item_types', 'gallery_groups_id', 'gallery_groups', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('items', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'item_types_id' => $this->integer(),
            'gallery_images_id' => $this->integer(),
            'contents_id' => $this->integer(),
            'title' => $this->string(255),
            'url' => $this->string(255),
            'price' => $this->decimal(13,2),
            'old_price' => $this->decimal(13,2),
            'seq' => $this->integer(),
            'state' => $this->boolean()->defaultValue(true),
        ], $tableOptions);

        $this->addForeignKey('fk-items-item_types_id', 'items', 'item_types_id', 'item_types', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-item-gallery_images_id', 'items', 'gallery_images_id', 'gallery_images', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-item-contents_id', 'items', 'contents_id', 'contents', 'id', 'CASCADE', 'CASCADE');

        $this->insert('modules', [
            'name' => 'Элементы контента',
            'url' => '/items/index',
            'visible' => true,
            'icon' => 'fa fa-id-card-o',
        ]);
    }

    public function down()
    {
        $this->delete('modules', ['url' => '/items/index']);
        $this->dropTable('items');
        $this->dropTable('item_types');
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
