<?php

use yii\db\Migration;

class m170330_185054_update_table_forms_add_field_created_at extends Migration
{
    public function up()
    {
        $this->addColumn('{{%forms}}', 'created_at', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('{{%forms}}', 'created_at');
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
