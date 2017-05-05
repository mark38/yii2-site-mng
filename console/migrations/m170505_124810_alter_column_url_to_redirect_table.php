<?php

use yii\db\Migration;

class m170505_124810_alter_column_url_to_redirect_table extends Migration
{
    public function up()
    {
        $this->alterColumn('redirects', 'url', $this->string(512));
    }

    public function down()
    {
        $this->alterColumn('redirects', 'url', $this->integer());
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
