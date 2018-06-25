<?php

use yii\db\Migration;

/**
 * Class m180625_112043_create_table_shop_categories_and_shop_goods_categories
 */
class m180625_112043_create_table_shop_categories_and_shop_goods_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%shop_categories}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->comment('Наименование категории')
        ]);

        $this->createTable('{{%shop_goods_categories}}', [
            'id' => $this->primaryKey(),
            'shop_goods_id' => $this->integer()->comment('ID товара'),
            'shop_categories_id' => $this->integer()->comment('ID категории')
        ]);

        $this->addForeignKey('fk-shop_goods_categories-shop_goods_id', '{{%shop_goods_categories}}', 'shop_goods_id', '{{%shop_goods}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk-shop_goods_categories-shop_categories_id', '{{%shop_goods_categories}}', 'shop_categories_id', '{{%shop_categories}}', 'id', 'SET NULL', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-shop_goods_categories-shop_goods_id', '{{%shop_goods_categories}}');
        $this->dropForeignKey('fk-shop_goods_categories-shop_categories_id', '{{%shop_goods_categories}}');

        $this->dropTable('{{%shop_categories}}');
        $this->dropTable('{{%shop_goods_categories}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180625_112043_create_table_shop_categories_and_shop_goods_categories cannot be reverted.\n";

        return false;
    }
    */
}
