<?php

use yii\db\Migration;

class m170413_051325_add_column_unit_to_shop_properties_table extends Migration
{
    public function up()
    {
        $this->addColumn('shop_properties', 'unit', $this->string(32));
    }

    public function down()
    {
        $this->dropColumn('shop_properties', 'unit');
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
