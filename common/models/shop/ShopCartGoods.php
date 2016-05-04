<?php

namespace common\models\shop;

use Yii;
use common\models\main\Sessions;

/**
 * This is the model class for table "shop_cart_goods".
 *
 * @property integer $id
 * @property integer $shop_carts_id
 * @property integer $shop_goods_id
 * @property integer $amount
 * @property string $price
 *
 * @property ShopGoods $shopGoods
 * @property ShopCarts $shopCarts
 */
class ShopCartGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_cart_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_carts_id', 'shop_goods_id', 'amount'], 'integer'],
            [['price'], 'number'],
            [['shop_goods_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopGoods::className(), 'targetAttribute' => ['shop_goods_id' => 'id']],
            [['shop_carts_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopCarts::className(), 'targetAttribute' => ['shop_carts_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_carts_id' => 'Shop Carts ID',
            'shop_goods_id' => 'Shop Goods ID',
            'amount' => 'Amount',
            'price' => 'Price',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopGood()
    {
        return $this->hasOne(ShopGoods::className(), ['id' => 'shop_goods_id']);
    }

    public function getShopPriceGoods()
    {
        return $this->hasMany(ShopPriceGood::className(), ['shop_goods_id' => 'id'])->via('shopGood');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopCart()
    {
        return $this->hasOne(ShopCarts::className(), ['id' => 'shop_carts_id']);
    }

    public function getSession()
    {
        return $this->hasOne(Sessions::className(), ['id' => 'sessions_id'])->via('shopCart');
    }
}
