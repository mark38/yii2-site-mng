<?php

use yii\db\Migration;

/**
 * Class m191108_165553_add_column_code_to_shop_units
 */
class m191108_165553_add_column_code_to_shop_units extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('shop_units', 'code', $this->string(32));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('shop_units', 'code');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191108_165553_add_column_code_to_shop_units cannot be reverted.\n";

        return false;
    }
    */
}
