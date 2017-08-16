<?php

namespace common\models\shop;

use common\models\main\Links;
use Yii;

/**
 * This is the model class for table "shop_good_properties".
 *
 * @property integer $id
 * @property integer $shop_goods_id
 * @property integer $shop_properties_id
 * @property integer $shop_property_values_id
 * @property integer $state
 *
 * @property ShopGoods $shopGood
 * @property ShopProperties $shopProperty
 * @property ShopPropertyValues $shopPropertyValue
 */
class ShopGoodProperties extends \yii\db\ActiveRecord
{
    public $amount;
    public $links_id;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_good_properties';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_goods_id', 'shop_properties_id', 'shop_property_values_id', 'state'], 'integer'],
            [['shop_goods_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopGoods::className(), 'targetAttribute' => ['shop_goods_id' => 'id']],
            [['shop_properties_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopProperties::className(), 'targetAttribute' => ['shop_properties_id' => 'id']],
            [['shop_property_values_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopPropertyValues::className(), 'targetAttribute' => ['shop_property_values_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_goods_id' => 'Shop Goods ID',
            'shop_properties_id' => 'Shop Properties ID',
            'shop_property_values_id' => 'Shop Property Values ID',
            'state' => 'State',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopGood()
    {
        return $this->hasOne(ShopGoods::className(), ['id' => 'shop_goods_id']);
    }

    public function getLink()
    {
        return $this->hasOne(Links::className(), ['id' => 'links_id'])->via('shopGood');
    }

    public function getShopPriceGood()
    {
        return $this->hasOne(ShopPriceGood::className(), ['shop_goods_id' => 'id'])->via('shopGood')->groupBy(['shop_goods_id'])->orderBy(['price' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopProperty()
    {
        return $this->hasOne(ShopProperties::className(), ['id' => 'shop_properties_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopPropertyValue()
    {
        return $this->hasOne(ShopPropertyValues::className(), ['id' => 'shop_property_values_id']);
    }
}
