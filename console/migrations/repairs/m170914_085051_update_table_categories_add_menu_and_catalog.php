<?php

use yii\db\Migration;

class m170914_085051_update_table_categories_add_menu_and_catalog extends Migration
{
    public function safeUp()
    {
        $this->update('{{categories}}', [
            'comment' => 'Каталог'
        ],[
            'name' => 'main'
        ]);
        $this->insert('{{categories}}', [
            'name' => 'top',
            'comment' => 'Меню сверху',
            'seq' => 2,
            'visible' => 1
        ]);
        $this->insert('{{categories}}', [
            'name' => 'other',
            'comment' => 'Дополнительные ссылки',
            'seq' => 3,
            'visible' => 1
        ]);
    }

    public function safeDown()
    {
        $this->update('{{categories}}', [
            'comment' => 'Main menu'
        ],[
            'name' => 'main'
        ]);
        $this->delete('{{categories}}', [
            'name' => 'top'
        ]);
        $this->delete('{{categories}}', [
            'name' => 'other'
        ]);
        $this->execute("ALTER TABLE categories AUTO_INCREMENT = 1");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170914_085051_update_table_categories_add_menu_and_catalog cannot be reverted.\n";

        return false;
    }
    */
}
