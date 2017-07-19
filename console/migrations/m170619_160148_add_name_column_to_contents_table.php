<?php

use yii\db\Migration;

/**
 * Handles adding name to table `contents`.
 */
class m170619_160148_add_name_column_to_contents_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('contents', 'name', $this->string(255));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('contents', 'name');
    }
}
