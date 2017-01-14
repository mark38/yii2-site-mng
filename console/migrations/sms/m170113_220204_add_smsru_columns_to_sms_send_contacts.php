<?php

use yii\db\Migration;

class m170113_220204_add_smsru_columns_to_sms_send_contacts extends Migration
{
    public function up()
    {
        $this->addColumn('sms_send_contacts', 'smsru_id', $this->string(128));
        $this->addColumn('sms_send_contacts', 'smsru_result_code', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('sms_send_contacts', 'smsru_result_code');
        $this->dropColumn('sms_send_contacts', 'smsru_id');

        return true;
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
