<?php

use yii\db\Migration;

/**
 * Class m171201_212514_add_unload_state_column
 */
class m171201_212514_add_export_state_columns extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('shop_groups', 'export_state', $this->boolean()->defaultValue(false));
        $this->addColumn('shop_goods', 'export_state', $this->boolean()->defaultValue(false));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('shop_groups', 'export_state');
        $this->dropColumn('shop_goods', 'export_state');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171201_212514_add_unload_state_column cannot be reverted.\n";

        return false;
    }
    */
}
