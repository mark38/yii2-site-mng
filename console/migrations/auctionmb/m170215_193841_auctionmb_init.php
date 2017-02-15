<?php

use yii\db\Migration;

class m170215_193841_auctionmb_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('auctionmb_users', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'name' => $this->string(255),
            'surname' => $this->string(255),
            'patronymic' => $this->string(255)
        ], $tableOptions);

        $this->addForeignKey('fk-auctionmb_users-user_id', 'auctionmb_users', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('auctionmb_types', [
            'id' => $this->primaryKey(),
            'links_id' => $this->integer(),
            'bets' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('fk-auctionmb_types-links_id', 'auctionmb_types', 'links_id', 'links', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('auctionmb_lots', [
            'id' => $this->primaryKey(),
            'auctionmb_types_id' => $this->integer(),
            'auctionmb_users_id' => $this->integer(),
            'state' => $this->boolean(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], $tableOptions);

        $this->addForeignKey('fk-auctionmb_lots-auctionmb_types_id', 'auctionmb_lots', 'auctionmb_types_id', 'auctionmb_types', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-auctionmb_lots-auctionmb_users_id', 'auctionmb_lots', 'auctionmb_users_id', 'auctionmb_users', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('auctionmb_bets', [
            'id' => $this->primaryKey(),
            'auctionmb_users_id' => $this->integer(),
            'auctionmb_lots_id' => $this->integer(),
            'created_at' => $this->integer()
        ], $tableOptions);

        $this->addForeignKey('fk-auctionmb_bets-auctionmb_users_id', 'auctionmb_bets', 'auctionmb_users_id', 'auctionmb_users', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-auctionmb_bets-auctionmb_lots_id', 'auctionmb_bets', 'auctionmb_lots_id', 'auctionmb_lots', 'id', 'CASCADE', 'CASCADE');

        $this->insert('modules', [
            'name' => 'Аукцион MB',
            'url' => '/auctionmb/index',
            'visible' => 1,
            'icon' => 'fa fa fa-gavel',
        ]);
    }

    public function down()
    {
        $this->dropTable('auctionmb_bets');
        $this->dropTable('auctionmb_lots');
        $this->dropTable('auctionmb_types');
        $this->dropTable('auctionmb_users');

        $this->delete('modules', ['url' => '/auctionmb/index']);
    }
}
