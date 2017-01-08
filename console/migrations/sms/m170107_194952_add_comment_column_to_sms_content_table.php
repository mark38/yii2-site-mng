<?php

use yii\db\Migration;

/**
 * Handles adding comment to table `sms_content`.
 */
class m170107_194952_add_comment_column_to_sms_content_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('sms_content', 'comment', $this->string(255));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('sms_content', 'comment');
    }
}
