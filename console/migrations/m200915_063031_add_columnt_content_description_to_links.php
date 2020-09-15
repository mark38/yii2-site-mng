<?php

use yii\db\Migration;

/**
 * Class m200915_063031_add_columnt_content_description_to_links
 */
class m200915_063031_add_columnt_content_description_to_links extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('links', 'content_description', $this->text());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('links', 'content_description');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200915_063031_add_columnt_content_description_to_links cannot be reverted.\n";

        return false;
    }
    */
}
