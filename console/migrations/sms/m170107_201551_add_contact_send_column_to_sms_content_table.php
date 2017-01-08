<?php

use yii\db\Migration;

/**
 * Handles adding contact_send to table `sms_content`.
 */
class m170107_201551_add_contact_send_column_to_sms_content_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('sms_content', 'contact_send', $this->boolean()->defaultValue(true));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('sms_content', 'contact_send');
    }
}
