<?php

namespace common\models\shop;

use Yii;

/**
 * This is the model class for table "shop_price_good".
 *
 * @property integer $id
 * @property integer $shop_price_types_id
 * @property integer $shop_goods_id
 * @property string $price
 *
 * @property ShopPriceType $shopPriceTypes
 * @property ShopGood $shopGoods
 */
class ShopPriceGood extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_price_good';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_price_types_id', 'shop_goods_id'], 'integer'],
            [['price'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_price_types_id' => 'Shop Price Types ID',
            'shop_goods_id' => 'Shop Goods ID',
            'price' => 'Price',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopPriceType()
    {
        return $this->hasOne(ShopPriceTypes::className(), ['id' => 'shop_price_types_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopGood()
    {
        return $this->hasOne(ShopGoods::className(), ['id' => 'shop_goods_id']);
    }
}
