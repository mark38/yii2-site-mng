<?php

namespace common\models\shop;

use common\models\gallery\GalleryImages;
use common\models\main\Links;
use Yii;

/**
 * This is the model class for table "shop_goods".
 *
 * @property integer $id
 * @property integer $shop_groups_id
 * @property string $links_id
 * @property string $code
 * @property string $name
 *
 * @property ShopGoodImages[] $shopGoodImages
 * @property ShopGroup $shopGroups
 * @property Link $links
 * @property ShopItems[] $shopItems
 * @property ShopPriceGood[] $shopPriceGoods
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
            [['shop_groups_id', 'links_id'], 'integer'],
            [['code', 'name'], 'string', 'max' => 255]
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
            'code' => 'Code',
            'name' => 'Name',
        ];
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLink()
    {
        return $this->hasOne(Links::className(), ['id' => 'links_id']);
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
        return $this->hasMany(ShopGoodProperties::className(), ['shop_goods_id' => 'id'])->innerJoinWith(['shopProperty'])->orderBy(['shop_properties.seq' => SORT_ASC]);
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
        return $this->hasMany(ShopGoodProperties::className(), ['shop_goods_id' => 'id']);
    }
}
