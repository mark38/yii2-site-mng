<?php

use yii\db\Migration;


class m170402_161551_insert_admin_in_rbac extends Migration
{
    public function up()
    {
        Yii::$app->runAction('migrate/up', [
            'migrationPath' => '@yii/rbac/migrations',
            'interactive' => false,
        ]);

        $this->insert('{{%auth_item}}', [
            'name' => 'admin',
            'type' => 1,
            'description' => 'Администратор',
            'created_at' => time()
        ]);

        $this->insert('{{%auth_assignment}}', [
            'item_name' => 'admin',
            'user_id' => 1,
            'created_at' => time()
        ]);
    }

    public function down()
    {
        $this->delete('{{%auth_assignment}}', [
            'user_id' => 1
        ]);

        $this->delete('{{%auth_item}}', [
            'name' => 'admin'
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
