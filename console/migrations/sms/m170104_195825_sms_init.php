<?php

use yii\db\Migration;

class m170104_195825_sms_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('sms_contacts', [
            'id' => $this->primaryKey(),
            'phone' => $this->string(255)->unique(),
            'name' => $this->string(255),
            'surname' => $this->string(255),
            'patronymic' => $this->string(255),
            'dob' => $this->date(),
            'female' => $this->boolean(),
            'male' => $this->string(),
            'email' => $this->string(255),
            'card_number' => $this->string(255),
            'delivery_address' => $this->string(512),
            'date_registration' => $this->date(),
            'control' => $this->boolean()->defaultValue(true),
            'state' => $this->boolean()->defaultValue(true),
            'created_at' => $this->integer(),
        ], $tableOptions);

        $this->createTable('sms_content', [
            'id' => $this->primaryKey(),
            'content' => $this->text(),
            'comment' => $this->string(255),
            'contact_send' => $this->boolean()->defaultValue(true),
            'created_at' => $this->integer()
        ], $tableOptions);

        $this->createTable('sms_send', [
            'id' => $this->primaryKey(),
            'sms_content_id' => $this->integer(),
            'status' => $this->boolean()->defaultValue(false),
            'created_at' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('fk-sms_send-sms_content_id', 'sms_send', 'sms_content_id', 'sms_content', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('sms_send_contacts', [
            'id' => $this->primaryKey(),
            'sms_send_id' => $this->integer(),
            'sms_contacts_id' => $this->integer(),
            'status' => $this->boolean()->defaultValue(false),
        ], $tableOptions);

        $this->addForeignKey('fk-sms_send_contacts-sms_send_id', 'sms_send_contacts', 'sms_send_id', 'sms_send', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-sms_send_contacts-sms_contacts_id', 'sms_send_contacts', 'sms_contacts_id', 'sms_contacts', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('sms_service_params', [
            'id' => $this->primaryKey(),
            'service_name' => $this->string(255),
            'smsru_api_id' => $this->string(255),
            'smsru_from' => $this->string(255)
        ], $tableOptions);

        $this->insert('sms_service_params', [
            'id' => 1
        ]);
    }

    public function down()
    {
        $this->dropTable('sms_service_params');
        $this->dropTable('sms_send_contacts');
        $this->dropTable('sms_send');
        $this->dropTable('sms_content');
        $this->dropTable('sms_contacts');
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
