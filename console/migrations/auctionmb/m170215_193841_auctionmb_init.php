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
            'friend_user_id' => $this->integer(),
            'balance_bets' => $this->integer(),
            'name' => $this->string(255),
            'phone' => $this->string(255),
        ], $tableOptions);
        $this->addForeignKey('fk-auctionmb_users-user_id', 'auctionmb_users', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-auctionmb_users-friend_user_id', 'auctionmb_users', 'friend_user_id', 'user', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('auctionmb_lots', [
            'id' => $this->primaryKey(),
            'links_id' => $this->integer(),
            'seconds' => $this->integer(),
            'bets' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('fk-auctionmb_lots-links_id', 'auctionmb_lots', 'links_id', 'links', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('auctionmb', [
            'id' => $this->primaryKey(),
            'auctionmb_lots_id' => $this->integer(),
            'auctionmb_users_id' => $this->integer(),
            'state' => $this->boolean(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], $tableOptions);

        $this->addForeignKey('fk-auctionmb-auctionmb_lots_id', 'auctionmb', 'auctionmb_lots_id', 'auctionmb_lots', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-auctionmb-auctionmb_users_id', 'auctionmb', 'auctionmb_users_id', 'auctionmb_users', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('auctionmb_bets', [
            'id' => $this->primaryKey(),
            'auctionmb_users_id' => $this->integer(),
            'auctionmb_id' => $this->integer(),
            'created_at' => $this->integer()
        ], $tableOptions);

        $this->addForeignKey('fk-auctionmb_bets-auctionmb_users_id', 'auctionmb_bets', 'auctionmb_users_id', 'auctionmb_users', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-auctionmb_bets-auctionmb_lots_id', 'auctionmb_bets', 'auctionmb_id', 'auctionmb', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('auctionmb_payment_systems', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'gallery_images_id' => $this->integer(),
            'state' => $this->boolean()->defaultValue(true),
        ]);
        $this->addForeignKey('fk-auctionmb_payment_systems-gallery_images_id', 'auctionmb_payment_systems', 'gallery_images_id', 'gallery_images', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('auctionmb_rates', [
            'id' => $this->primaryKey(),
            'cost' => $this->decimal(12,2),
            'bets' => $this->integer(),
            'state' => $this->boolean()->defaultValue(true),
            'seq' => $this->integer()
        ], $tableOptions);

        $this->createTable('auctionmb_taking_types', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'description' => $this->string(1024)
        ], $tableOptions);
        $this->insert('auctionmb_taking_types', [
            'id' => 1,
            'name' => 'Пополнение баланса',
            'description' => 'Самостоятельное пополнение баланса через платёжные системы'
        ]);
        $this->insert('auctionmb_taking_types', [
            'id' => 2,
            'name' => 'Привличение клиента',
            'description' => 'Регистрация по реферальной ссылке и пополнение баланса'
        ]);
        $this->insert('auctionmb_taking_types', [
            'id' => 3,
            'name' => 'Бонус от администрации',
            'description' => 'Подарочное пополнение баланса от администрации проекта'
        ]);

        $this->createTable('auctionmb_takings', [
            'id' => $this->primaryKey(),
            'auctionmb_users_id' => $this->integer(),
            'auctionmb_rates_id' => $this->integer(),
            'auctionmb_payment_systems_id' => $this->integer(),
            'auctionmb_taking_types_id' => $this->integer()->defaultValue(1),
            'paid' => $this->decimal(12,2),
            'bets' => $this->integer(),
            'comment' => $this->string(512),
        ], $tableOptions);
        $this->addForeignKey('fk-auctionmb_takings-auctionmb_users_id', 'auctionmb_takings', 'auctionmb_users_id', 'auctionmb_users', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-auctionmb_takings-auctionmb_rates_id', 'auctionmb_takings', 'auctionmb_rates_id', 'auctionmb_rates', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk-auctionmb_takings-auctionmb_payment_systems_id', 'auctionmb_takings', 'auctionmb_payment_systems_id', 'auctionmb_payment_systems', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk-auctionmb_takings-auctionmb_taking_types_id', 'auctionmb_takings', 'auctionmb_taking_types_id', 'auctionmb_taking_types', 'id', 'SET NULL', 'CASCADE');

        $this->insert('modules', [
            'name' => 'Аукцион MB',
            'url' => '/auctionmb/index',
            'visible' => 1,
            'icon' => 'fa fa fa-gavel',
        ]);
    }

    public function down()
    {
        $this->dropTable('auctionmb_takings');
        $this->dropTable('auctionmb_taking_types');
        $this->dropTable('auctionmb_rates');
        $this->dropTable('auctionmb_payment_systems');
        $this->dropTable('auctionmb_bets');
        $this->dropTable('auctionmb');
        $this->dropTable('auctionmb_lots');
        $this->dropTable('auctionmb_users');

        $this->delete('modules', ['url' => '/auctionmb/index']);
    }
}
