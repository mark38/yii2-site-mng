<?php

namespace common\models\shop;

use Yii;
use common\models\main\Sessions;

/**
 * This is the model class for table "shop_good_favorites".
 *
 * @property integer $id
 * @property integer $sessions_id
 * @property integer $shop_goods_id
 * @property integer $created_at
 *
 * @property Sessions $sessions
 * @property ShopGoods $shopGoods
 */
class ShopGoodFavorites extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_good_favorites';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sessions_id', 'shop_goods_id', 'created_at'], 'integer']
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
            'shop_goods_id' => 'Shop Goods ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSession()
    {
        return $this->hasOne(Sessions::className(), ['id' => 'sessions_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopGood()
    {
        return $this->hasOne(ShopGoods::className(), ['id' => 'shop_goods_id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->created_at = time();
        }

        return true;
    }
}
