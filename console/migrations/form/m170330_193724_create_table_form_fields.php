<?php

use yii\db\Migration;

class m170330_193724_create_table_form_fields extends Migration
{
    public function up()
    {
        $this->createTable('{{%form_fields}}', [
            'id' => $this->primaryKey(),
            'form_types_id' => $this->integer()->comment('Тип формы'),
            'name' => $this->string(255)->comment('Наименование'),
            'visible' => $this->boolean()->comment('Статус')
        ]);

        $this->addForeignKey(
            '{{%fk-form_fields-user_id}}',
            '{{%form_fields}}',
            'form_types_id',
            'form_types',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('{{%fk-form_fields-user_id}}', '{{%form_fields}}');
        $this->dropTable('{{%form_fields}}');
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
