<?php

use yii\db\Migration;

/**
 * Handles adding number to table `shop_properties`.
 */
class m171216_091427_add_number_column_to_shop_properties_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('shop_properties', 'number', $this->boolean(false));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('shop_properties', 'number');
    }
}
