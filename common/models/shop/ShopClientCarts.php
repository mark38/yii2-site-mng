<?php

namespace common\models\shop;

use Yii;

/**
 * This is the model class for table "shop_client_carts".
 *
 * @property integer $id
 * @property integer $shop_clients_id
 * @property integer $shop_carts_id
 * @property string $comment
 *
 * @property ShopClients $shopClients
 * @property ShopCarts $shopCarts
 */
class ShopClientCarts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_client_carts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_clients_id', 'shop_carts_id'], 'integer'],
            [['comment'], 'string', 'max' => 2048],
            [['shop_clients_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopClients::className(), 'targetAttribute' => ['shop_clients_id' => 'id']],
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
            'shop_clients_id' => 'Shop Clients ID',
            'shop_carts_id' => 'Shop Carts ID',
            'comment' => 'Comment',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopClients()
    {
        return $this->hasOne(ShopClients::className(), ['id' => 'shop_clients_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopCarts()
    {
        return $this->hasOne(ShopCarts::className(), ['id' => 'shop_carts_id']);
    }
}
