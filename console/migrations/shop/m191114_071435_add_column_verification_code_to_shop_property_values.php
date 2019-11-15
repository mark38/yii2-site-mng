<?php

use yii\db\Migration;

/**
 * Class m191114_071435_add_column_verification_code_to_shop_property_values
 */
class m191114_071435_add_column_verification_code_to_shop_property_values extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('shop_property_values', 'verification_code', $this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('shop_property_values', 'verification_code');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191114_071435_add_column_verification_code_to_shop_property_values cannot be reverted.\n";

        return false;
    }
    */
}
