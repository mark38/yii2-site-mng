<?php

use yii\db\Migration;

/**
 * Handles the creation of table `subscribers`.
 */
class m161225_083003_create_broadcast_subscribers_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('broadcast_subscribers', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'email' => $this->string(255),
            'referral_link' => $this->string(255),
            'state' => $this->boolean()->defaultValue(true)
        ], $tableOptions);

        $this->addForeignKey('fk-broadcast_subscribers-user_id', 'broadcast_subscribers', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('broadcast_subscribers');
    }
}
