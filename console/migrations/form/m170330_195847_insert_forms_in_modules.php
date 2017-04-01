<?php

use yii\db\Migration;

class m170330_195847_insert_forms_in_modules extends Migration
{
    public function up()
    {
        $this->insert('{{%modules}}', [
            'name' => 'Формы',
            'url' => '/forms/index',
            'visible' => true,
            'icon' => 'fa fa-server'
        ]);
    }

    public function down()
    {
        $this->delete('{{%modules}}', [
            'name' => 'Формы'
        ]);
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
