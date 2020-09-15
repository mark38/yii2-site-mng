<?php

namespace common\models\shop;

use Yii;

/**
 * This is the model class for table "shop_items".
 *
 * @property integer $id
 * @property integer $shop_goods_id
 * @property string $verification_code
 * @property integer $state
 *
 * @property ShopItemCharacteristics[] $shopItemCharacteristics
 * @property ShopGood $shopGoods
 * @property ShopPriceItem[] $shopPriceItems
 */
class ShopItems extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_items';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_goods_id', 'state'], 'integer'],
            [['verification_code'], 'string', 'max' => 255]
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
            'verification_code' => 'Verification Code',
            'state' => 'State',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopItemCharacteristics()
    {
        return $this->hasMany(ShopItemCharacteristics::className(), ['shop_items_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopGood()
    {
        return $this->hasOne(ShopGoods::className(), ['id' => 'shop_goods_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopPriceItems()
    {
        return $this->hasMany(ShopPriceItem::className(), ['shop_items_id' => 'id']);
    }
}
