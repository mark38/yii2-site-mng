<?php

use yii\db\Migration;

class m160728_212221_shop_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('shop_users', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'name' => $this->string(255),
            'surname' => $this->string(255),
            'patronymic' => $this->string(255)
        ], $tableOptions);

        $this->addForeignKey('fk-shop_users-user_id', 'shop_users', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('shop_groups', [
            'id' => $this->primaryKey(),
            'links_id' => $this->integer(),
            'verification_code' => $this->string(255),
            'name' => $this->string(255)
        ], $tableOptions);

        $this->addForeignKey('fk-shop_groups-links_id', 'shop_groups', 'links_id', 'links', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('shop_units', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
        ], $tableOptions);

        $this->createTable('shop_goods', [
            'id' => $this->primaryKey(),
            'shop_groups_id' => $this->integer(),
            'links_id' => $this->integer(),
            'shop_units_id' => $this->integer(),
            'verification_code' => $this->string(255),
            'name' => $this->string(255),
            'code' => $this->string(255),
            'state' => $this->boolean()->defaultValue(1)
        ], $tableOptions);

        $this->addForeignKey('fk-shop_goods-shop_groups_id', 'shop_goods', 'shop_groups_id', 'shop_groups', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-shop_goods-links_id', 'shop_goods', 'links_id', 'links', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-shop_goods-shop_units_id', 'shop_goods', 'shop_units_id', 'shop_units', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('shop_good_viewed', [
            'id' => $this->primaryKey(),
            'sessions_id' => $this->integer(),
            'shop_goods_id' => $this->integer(),
            'created_at' => $this->integer()
        ], $tableOptions);

        $this->addForeignKey('fk-shop_good_viewed-sessions_id', 'shop_good_viewed', 'sessions_id', 'sessions', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-shop_good_viewed-shop_goods_id', 'shop_good_viewed', 'shop_goods_id', 'shop_goods', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('shop_good_favorites', [
            'id' => $this->primaryKey(),
            'sessions_id' => $this->integer(),
            'shop_goods_id' => $this->integer(),
            'created_at' => $this->integer()
        ], $tableOptions);

        $this->addForeignKey('fk-shop_good_favorites-sessions_id', 'shop_good_favorites', 'sessions_id', 'sessions', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-shop_good_favorites-shop_goods_id', 'shop_good_favorites', 'shop_goods_id', 'shop_goods', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('shop_good_gallery', [
            'id' => $this->primaryKey(),
            'shop_goods_id' => $this->integer(),
            'gallery_types_id' => $this->integer(),
            'gallery_groups_id' => $this->integer()
        ], $tableOptions);

        $this->addForeignKey('fk-shop_good_gallery-shop_goods_id', 'shop_good_gallery', 'shop_goods_id', 'shop_goods', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-shop_good_gallery-gallery_types_id', 'shop_good_gallery', 'gallery_types_id', 'gallery_types', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-shop_good_gallery-gallery_groups_id', 'shop_good_gallery', 'gallery_groups_id', 'gallery_groups', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('shop_items', [
            'id' => $this->primaryKey(),
            'shop_goods_id' => $this->integer(),
            'verification_code' => $this->string(255),
            'state' => $this->boolean()->defaultValue(1)
        ], $tableOptions);

        $this->addForeignKey('fk-shop_items-shop_goods_id', 'shop_items', 'shop_goods_id', 'shop_goods', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('shop_characteristics', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)
        ], $tableOptions);

        $this->createTable('shop_item_characteristics', [
            'id' => $this->primaryKey(),
            'shop_items_id' => $this->integer(),
            'shop_characteristics_id' => $this->integer(),
            'name' => $this->string(255),
            'state' => $this->boolean()->defaultValue(1)
        ], $tableOptions);

        $this->addForeignKey('fk-shop_item_characteristics-shop_items_id', 'shop_item_characteristics', 'shop_items_id', 'shop_items', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-shop_item_characteristics-shop_characteristics_id', 'shop_item_characteristics', 'shop_characteristics_id', 'shop_characteristics', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('shop_price_types', [
            'id' => $this->primaryKey(),
            'verification_code' => $this->string(255),
            'name' => $this->string(255),
            'def' => $this->boolean()
        ], $tableOptions);

        $this->createTable('shop_price_good', [
            'id' => $this->primaryKey(),
            'shop_price_types_id' => $this->integer(),
            'shop_goods_id' => $this->integer(),
            'price' => $this->decimal(13,2)
        ], $tableOptions);

        $this->addForeignKey('fk-shop_price_good-shop_price_types_id', 'shop_price_good', 'shop_price_types_id', 'shop_price_types', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-shop_price_good-shop_goods_id', 'shop_price_good', 'shop_goods_id', 'shop_goods', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('shop_price_item', [
            'id' => $this->primaryKey(),
            'shop_price_types_id' => $this->integer(),
            'shop_items_id' => $this->integer(),
            'price' => $this->decimal(13,2)
        ], $tableOptions);

        $this->addForeignKey('fk-shop_price_item-shop_price_types_id', 'shop_price_item', 'shop_price_types_id', 'shop_price_types', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-shop_price_item-shop_items_id', 'shop_price_item', 'shop_items_id', 'shop_items', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('shop_properties', [
            'id' => $this->primaryKey(),
            'verification_code' => $this->string(255),
            'name' => $this->string(255),
            'anchor' => $this->string(255),
            'url' => $this->string(255),
            'seq' => $this->integer(),
            'range' => $this->boolean()->defaultValue(false),
            'state' => $this->boolean()->defaultValue(true)
        ], $tableOptions);

        $this->createTable('shop_property_values', [
            'id' => $this->primaryKey(),
            'shop_properties_id' => $this->integer(),
            'name' => $this->string(255),
            'anchor' => $this->string(255),
            'url' => $this->string(255),
            'contents_id' => $this->integer()
        ], $tableOptions);

        $this->addForeignKey('fk-shop_property_values-shop_properties_id', 'shop_property_values', 'shop_properties_id', 'shop_properties', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-shop_property_values-contents_id', 'shop_property_values', 'contents_id', 'shop_property_values', 'id', 'SET NULL', 'CASCADE');

        $this->createTable('shop_group_properties', [
            'id' => $this->primaryKey(),
            'shop_groups_id' => $this->integer(),
            'shop_properties_id' => $this->integer(),
            'state' => $this->boolean()->defaultValue(true)
        ], $tableOptions);

        $this->addForeignKey('fk-shop_group_properties-shop_groups_id', 'shop_group_properties', 'shop_groups_id', 'shop_groups', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('shop_good_properties', [
            'id' => $this->primaryKey(),
            'shop_goods_id' => $this->integer(),
            'shop_properties_id' => $this->integer(),
            'shop_property_values_id' => $this->integer(),
            'state' => $this->boolean()->defaultValue(true)
        ], $tableOptions);

        $this->addForeignKey('fk-shop_good_properties-shop_goods_id', 'shop_good_properties', 'shop_goods_id', 'shop_goods', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-shop_good_properties-shop_properties_id', 'shop_good_properties', 'shop_properties_id', 'shop_properties', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-shop_good_properties-shop_property_values_id', 'shop_good_properties', 'shop_property_values_id', 'shop_property_values', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('shop_carts', [
            'id' => $this->primaryKey(),
            'sessions_id' => $this->integer(),
            'state' => $this->boolean()->defaultValue(true),
            'created_at' => $this->integer(),
            'checkout_at' => $this->integer()
        ], $tableOptions);

        $this->addForeignKey('fk-shop_carts-sessions_id', 'shop_carts', 'sessions_id', 'sessions', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('shop_cart_goods', [
            'id' => $this->primaryKey(),
            'shop_carts_id' => $this->integer(),
            'shop_goods_id' => $this->integer(),
            'amount' => $this->integer(),
            'price' => $this->decimal(13,2)
        ], $tableOptions);

        $this->addForeignKey('fk-shop_cart_goods-shop_carts_id', 'shop_cart_goods', 'shop_carts_id', 'shop_carts', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-shop_cart_goods-shop_goods_id', 'shop_cart_goods', 'shop_goods_id', 'shop_goods', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('shop_cart_items', [
            'id' => $this->primaryKey(),
            'shop_carts_id' => $this->integer(),
            'shop_items_id' => $this->integer(),
            'amount' => $this->integer(),
            'price' => $this->decimal(13,2)
        ], $tableOptions);

        $this->addForeignKey('fk-shop_cart_items-shop_carts_id', 'shop_cart_items', 'shop_carts_id', 'shop_carts', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-shop_cart_items-shop_items_id', 'shop_cart_items', 'shop_items_id', 'shop_items', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('shop_clients', [
            'id' => $this->primaryKey(),
            'shop_users_id' => $this->integer(),
            'fio' => $this->string(255),
            'city' => $this->string(255),
            'street' => $this->string(255),
            'home_number' => $this->string(255),
            'flat_number' => $this->string(255),
            'phone' => $this->string(255),
            'email' => $this->string(255),
            'comment' => $this->string(2048)
        ], $tableOptions);

        $this->addForeignKey('fk-shop_clients-shop_users_id', 'shop_clients', 'shop_users_id', 'shop_users', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('shop_client_carts', [
            'id' => $this->primaryKey(),
            'shop_clients_id' => $this->integer(),
            'shop_carts_id' => $this->integer(),
            'comment' => $this->string(2048)
        ], $tableOptions);

        $this->addForeignKey('fk-shop_client_carts-shop_clients_id', 'shop_client_carts', 'shop_clients_id', 'shop_clients', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-shop_client_carts-shop_carts_id', 'shop_client_carts', 'shop_carts_id', 'shop_carts', 'id', 'CASCADE', 'CASCADE');

        $this->insert('modules', [
            'name' => 'Интернет-магазин',
            'url' => '/shop/index',
            'visible' => 1,
            'icon' => 'fa fa-shopping-cart',
        ]);
    }

    public function down()
    {
        $this->dropTable('shop_client_carts');
        $this->dropTable('shop_clients');

        $this->dropTable('shop_cart_items');
        $this->dropTable('shop_cart_goods');
        $this->dropTable('shop_carts');

        $this->dropTable('shop_good_properties');
        $this->dropTable('shop_group_properties');
        $this->dropTable('shop_property_values');
        $this->dropTable('shop_properties');
        $this->dropTable('shop_price_item');
        $this->dropTable('shop_price_good');
        $this->dropTable('shop_price_types');
        $this->dropTable('shop_item_characteristics');
        $this->dropTable('shop_characteristics');
        $this->dropTable('shop_items');
        $this->dropTable('shop_good_gallery');
        $this->dropTable('shop_good_favorites');
        $this->dropTable('shop_good_viewed');
        $this->dropTable('shop_goods');
        $this->dropTable('shop_units');
        $this->dropTable('shop_groups');
        $this->dropTable('shop_users');

        $this->delete('modules', ['url' => '/shop/index']);
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
