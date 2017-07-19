<?php

use yii\db\Migration;

class m170616_132959_add_links_id_item_types_table extends Migration
{
    public function up()
    {
        $this->addColumn('item_types', 'links_id', $this->integer());
        $this->addForeignKey('fk-item_types-links_id', 'item_types', 'links_id', 'links', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk-item_types-links_id', 'item_types');
        $this->dropColumn('item_types', 'links_id');
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
