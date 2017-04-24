<?php

use yii\db\Migration;

/**
 * Handles adding parent_id to table `shop_groups`.
 */
class m170424_125341_add_parent_id_column_to_shop_groups_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('shop_groups', 'parent_id', $this->integer());
        $this->addForeignKey('fk-shop_groups-parent_id', 'shop_groups', 'parent_id', 'shop_groups', 'id', 'cascade', 'cascade');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-shop_groups-parent_id');
        $this->dropColumn('shop_groups');
    }
}
