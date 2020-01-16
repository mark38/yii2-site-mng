<?php

use yii\db\Migration;

/**
 * Class m200116_081937_add_virtual_url_to_links
 */
class m200116_081937_add_virtual_url_to_links extends Migration
{

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('links', 'virtual_url', $this->string(255));
    }

    public function down()
    {
        echo "m200116_081937_add_virtual_url_to_links cannot be reverted.\n";

        $this->dropColumn('links', 'virtual_url');
    }

}
