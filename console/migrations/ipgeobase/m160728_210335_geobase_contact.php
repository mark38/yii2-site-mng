<?php

use yii\db\Migration;

class m160728_210335_geobase_contact extends Migration
{
    const DB_CONTACT_TABLE_NAME = '{{%geobase_contact}}';
    const DB_CITY_TABLE_NAME = '{{%geobase_city}}';

    public function up()
    {
        $this->createTable(self::DB_CONTACT_TABLE_NAME, [
            'id' => $this->primaryKey(),
            'geobase_city_id' => $this->integer(6)->unsigned(),
            'city_name' => $this->string(255),
            'phone' => $this->string(255),
            'address' => $this->string(255),
            'working_hours' => $this->string(255),
            'def' => $this->boolean(),
            'seq' => $this->integer()
        ]);

        $this->addForeignKey('fk-geobase_contact-geobase_city_id', self::DB_CONTACT_TABLE_NAME, 'geobase_city_id', self::DB_CITY_TABLE_NAME, 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable(self::DB_CONTACT_TABLE_NAME);
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
