<?php

use yii\db\Migration;

class m161119_214206_ratings extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('ratings', [
            'id' => $this->primaryKey(),
            'links_id' => $this->integer(),
            'user_id' => $this->integer(),
            'sessions_id' => $this->integer(),
            'value' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('fk-ratings-links_id', 'ratings', 'links_id', 'links', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-ratings-user_id', 'ratings', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-ratings-session_id', 'ratings', 'sessions_id', 'sessions', 'id', 'CASCADE', 'CASCADE');

        $this->addColumn('links', 'avg_rating', $this->decimal(5,1)->defaultValue(0));
    }

    public function down()
    {
        $this->dropTable('ratings');
        $this->dropColumn('links', 'avg_rating');
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
