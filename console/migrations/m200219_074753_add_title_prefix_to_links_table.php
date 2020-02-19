<?php

use yii\db\Migration;

/**
 * Class m200219_074753_add_title_prefix_to_links_table
 */
class m200219_074753_add_title_prefix_to_links_table extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('links', 'title_prefix', $this->string(255));
    }

    public function down()
    {
        $this->dropColumn('links', 'title_prefix');
    }

}
