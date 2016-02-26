<?php

namespace common\models\shop;

use Yii;

/**
 * This is the model class for table "shop_price_item".
 *
 * @property integer $id
 * @property integer $shop_price_types_id
 * @property integer $shop_items_id
 * @property string $price
 *
 * @property ShopPriceType $shopPriceTypes
 * @property ShopItem $shopItems
 */
class ShopPriceItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_price_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_price_types_id', 'shop_items_id'], 'integer'],
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
            'shop_items_id' => 'Shop Items ID',
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
    public function getShopItem()
    {
        return $this->hasOne(ShopItems::className(), ['id' => 'shop_items_id']);
    }
}
