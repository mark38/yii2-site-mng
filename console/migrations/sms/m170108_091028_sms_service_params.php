<?php

use yii\db\Migration;

class m170108_091028_sms_service_params extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('sms_service_params', [
            'service_name' => $this->string(255),
            'smsru_api_id' => $this->string(255)
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('sms_service_params');
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
