<?php

namespace common\models\shop;

use Yii;
use common\models\main\Sessions;

/**
 * This is the model class for table "shop_carts".
 *
 * @property integer $id
 * @property integer $sessions_id
 * @property integer $state
 * @property integer $created_at
 * @property integer $checkout_at
 *
 * @property ShopCartGoods[] $shopCartGoods
 * @property ShopCartItems[] $shopCartItems
 * @property Sessions $sessions
 */
class ShopCarts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_carts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sessions_id', 'state', 'created_at', 'checkout_at'], 'integer'],
            [['sessions_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sessions::className(), 'targetAttribute' => ['sessions_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sessions_id' => 'Sessions ID',
            'state' => 'State',
            'created_at' => 'Created At',
            'checkout_at' => 'Checkout At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopCartGoods()
    {
        return $this->hasMany(ShopCartGoods::className(), ['shop_carts_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopCartItems()
    {
        return $this->hasMany(ShopCartItems::className(), ['shop_carts_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSession()
    {
        return $this->hasOne(Sessions::className(), ['id' => 'sessions_id']);
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->created_at = time();
        }

        return true;
    }
}
