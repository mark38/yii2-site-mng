<?php

namespace common\models\shop;

use Yii;

/**
 * This is the model class for table "shop_item_characteristics".
 *
 * @property integer $id
 * @property integer $shop_items_id
 * @property integer $shop_characteristics_id
 * @property string $name
 * @property integer $state
 *
 * @property ShopItem $shopItems
 * @property ShopCharacteristics $shopCharacteristics
 */
class ShopItemCharacteristics extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_item_characteristics';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_items_id', 'shop_characteristics_id', 'state'], 'integer'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_items_id' => 'Shop Items ID',
            'shop_characteristics_id' => 'Shop Characteristics ID',
            'name' => 'Name',
            'state' => 'State',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopItem()
    {
        return $this->hasOne(ShopItems::className(), ['id' => 'shop_items_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopCharacteristic()
    {
        return $this->hasOne(ShopCharacteristics::className(), ['id' => 'shop_characteristics_id']);
    }
}
