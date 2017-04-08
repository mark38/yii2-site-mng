<?php

use yii\db\Migration;

class m170408_204519_add_label_column_in_form_fields extends Migration
{
    public function up()
    {
        $this->addColumn('{{%form_fields}}', 'label', $this->string(255));
    }

    public function down()
    {
        $this->dropColumn('{{%form_fields}}', 'label');
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
