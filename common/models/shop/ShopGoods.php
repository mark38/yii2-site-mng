<?php

namespace common\models\shop;

use common\models\gallery\GalleryImages;
use common\models\main\Ancestors;
use common\models\main\Links;
use Yii;

/**
 * This is the model class for table "shop_goods".
 *
 * @property integer $id
 * @property integer $shop_groups_id
 * @property integer $links_id
 * @property integer $shop_units_id
 * @property string $verification_code
 * @property string $name
 * @property string $code
 * @property string $state
 * @property integer $items_exist
 *
 * @property ShopGoodGallery $shopGoodGallery
 * @property ShopGoodImages[] $shopGoodImages
 * @property ShopGroups $shopGroup
 * @property Links $link
 * @property ShopItems[] $shopItems
 * @property ShopUnits $shopUnit
 * @property ShopPriceGood[] $shopPriceGoods
 * @property ShopProperties[] $shopProperties
 */
class ShopGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_groups_id', 'links_id', 'shop_units_id', 'state', 'items_exist'], 'integer'],
            [['verification_code', 'name', 'code'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_groups_id' => 'Shop Groups ID',
            'links_id' => 'Links ID',
            'shop_units_id' => 'Shop Units Id',
            'verification_code' => 'Verification Code',
            'name' => 'Name',
            'code' => 'Code',
            'state' => 'State',
            'items_exist' => 'Items Exist',
        ];
    }

    public function getShopGoodGallery()
    {
        return $this->hasOne(ShopGoodGallery::className(), ['shop_goods_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopGoodImages()
    {
        return $this->hasMany(ShopGoodImages::className(), ['shop_goods_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopGroup()
    {
        return $this->hasOne(ShopGroups::className(), ['id' => 'shop_groups_id']);
    }

    public function getGroupActiveLink()
    {
        return $this->hasOne(Links::className(), ['id' => 'links_id'])->where(['links.child_exist' => true, 'links.state' => true])->via('shopGroup');
    }

    public function getAncestorActiveGroup()
    {
        return $this->hasOne(Ancestors::className(), ['links_id' => 'id'])->via('groupActiveLink');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLink()
    {
        return $this->hasOne(Links::className(), ['id' => 'links_id']);
    }

    public function getShopUnit()
    {
        return $this->hasOne(ShopUnits::className(), ['id' => 'shop_units_id']);
    }

    public function getGalleryImage()
    {
        return $this->hasOne(GalleryImages::className(), ['id' => 'gallery_images_id'])->via('link');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopItems()
    {
        return $this->hasMany(ShopItems::className(), ['shop_goods_id' => 'id']);
    }

    public function getCharacteristics()
    {
        return $this->hasMany(ShopItemCharacteristics::className(), ['shop_items_id' => 'id'])->groupBy(['name'])->orderBy(['name' => SORT_ASC])->via('shopItems');
    }

    public function getProperties()
    {
        return $this->hasMany(ShopGoodProperties::className(), ['shop_goods_id' => 'id'])->innerJoinWith(['shopProperty'])->where(['shop_properties.state' => 1])->orderBy(['shop_properties.seq' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopPriceGoods()
    {
        return $this->hasMany(ShopPriceGood::className(), ['shop_goods_id' => 'id']);
    }

    public function getShopGoodProperties()
    {
//        return $this->hasMany(ShopGoodProperties::className(), ['shop_goods_id' => 'id'])->innerJoin('shop_properties')->orderBy(['shop_properties.seq' => SORT_ASC]);
        return $this->hasMany(ShopGoodProperties::className(), ['shop_goods_id' => 'id']);
    }

    public function getShopPropertyValue()
    {
        return $this->hasOne(ShopPropertyValues::className(), ['id' => 'shop_property_values_id'])->via('shopGoodProperties');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopGoodsCategories()
    {
        return $this->hasMany(ShopGoodsCategories::className(), ['shop_categories_id' => 'id']);
    }
}
