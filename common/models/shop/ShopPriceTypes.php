<?php

namespace common\models\shop;

use Yii;

/**
 * This is the model class for table "shop_price_types".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property integer $def
 *
 * @property ShopPriceGood[] $shopPriceGoods
 * @property ShopPriceItem[] $shopPriceItems
 */
class ShopPriceTypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_price_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['def'], 'integer'],
            [['code', 'name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'def' => 'Def',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopPriceGoods()
    {
        return $this->hasMany(ShopPriceGood::className(), ['shop_price_types_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopPriceItems()
    {
        return $this->hasMany(ShopPriceItem::className(), ['shop_price_types_id' => 'id']);
    }
}
