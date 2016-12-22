<?php

use yii\db\Migration;

/**
 * Handles adding gallery_images_id to table `shop_property_values`.
 */
class m161222_072054_add_gallery_images_id_column_to_shop_property_values_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropForeignKey('fk-shop_property_values-contents_id', 'shop_property_values');
        $this->addForeignKey('fk-shop_property_values-contents_id', 'shop_property_values', 'contents_id', 'contents', 'id', 'SET NULL', 'CASCADE');

        $this->addColumn('shop_property_values', 'gallery_images_id', $this->integer());

        $this->addForeignKey('fk-shop_property_values-gallery_images_id', 'shop_property_values', 'gallery_images_id', 'gallery_images', 'id', 'SET NULL', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-shop_property_values-contents_id', 'shop_property_values');
        $this->addForeignKey('fk-shop_property_values-contents_id', 'shop_property_values', 'contents_id', 'shop_property_values', 'id', 'SET NULL', 'CASCADE');
        $this->dropForeignKey('fk-shop_property_values-gallery_images_id', 'shop_property_values');
        $this->dropColumn('shop_property_values', 'gallery_images_id');
    }
}
