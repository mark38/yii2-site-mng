<?php

namespace common\models\shop;

use common\models\main\Links;
use Yii;
use common\models\main\Sessions;

/**
 * This is the model class for table "shop_cart_items".
 *
 * @property integer $id
 * @property integer $shop_carts_id
 * @property integer $shop_items_id
 * @property integer $amount
 * @property string $price
 *
 * @property ShopItems $shopItems
 * @property ShopCarts $shopCarts
 */
class ShopCartItems extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_cart_items';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_carts_id', 'shop_items_id', 'amount'], 'integer'],
            [['price'], 'number'],
            [['shop_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopItems::className(), 'targetAttribute' => ['shop_items_id' => 'id']],
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
            'shop_items_id' => 'Shop Items ID',
            'amount' => 'Amount',
            'price' => 'Price',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopItem()
    {
        return $this->hasOne(ShopItems::className(), ['id' => 'shop_items_id']);
    }

    public function getShopGood()
    {
        return $this->hasOne(ShopGoods::className(), ['id' => 'shop_goods_id'])->via('shopItem');
    }

    public function getShopItemCharacteristics()
    {
        return $this->hasMany(ShopItemCharacteristics::className(), ['shop_items_id' => 'id'])->via('shopItem');
    }

    public function getLink()
    {
        return $this->hasOne(Links::className(), ['id' => 'links_id'])->via('shopGood');
    }

    public function getShopPriceItems()
    {
        return $this->hasMany(ShopPriceItem::className(), ['shop_items_id' => 'id'])->via('shopItem');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopCart()
    {
        return $this->hasOne(ShopCarts::className(), ['id' => 'shop_carts_id']);
    }

    public function getShopClientCart()
    {
        return $this->hasOne(ShopClientCarts::className(), ['shop_carts_id' => 'id'])->via('shopCart');
    }

    public function getShopClient()
    {
        return $this->hasOne(ShopClients::className(), ['id' => 'shop_clients_id'])->via('shopClientCart');
    }

    public function getSession()
    {
        return $this->hasOne(Sessions::className(), ['id' => 'sessions_id'])->via('shopCart');
    }
}
