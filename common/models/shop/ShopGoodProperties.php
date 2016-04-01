<?php

namespace common\models\shop;

use Yii;

/**
 * This is the model class for table "shop_good_properties".
 *
 * @property integer $id
 * @property integer $shop_goods_id
 * @property integer $shop_properties_id
 * @property integer $shop_property_values_id
 *
 * @property ShopGoods $shopGoods
 * @property ShopProperties $shopProperties
 * @property ShopPropertyValues $shopPropertyValues
 */
class ShopGoodProperties extends \yii\db\ActiveRecord
{
    public $amount;

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
            [['shop_goods_id', 'shop_properties_id', 'shop_property_values_id'], 'integer'],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopGoods()
    {
        return $this->hasOne(ShopGoods::className(), ['id' => 'shop_goods_id']);
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
