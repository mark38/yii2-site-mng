<?php

use yii\db\Migration;

/**
 * Handles adding h1 to table `links`.
 */
class m170405_073737_add_h1_column_to_links_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('links', 'h1', $this->string(255));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('links', 'h1');
    }
}
