<?php

namespace common\models\shop;

use Yii;

/**
 * This is the model class for table "shop_characteristics".
 *
 * @property integer $id
 * @property string $name
 *
 * @property ShopItemCharacteristics[] $shopItemCharacteristics
 */
class ShopCharacteristics extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_characteristics';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
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
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopItemCharacteristics()
    {
        return $this->hasMany(ShopItemCharacteristics::className(), ['shop_characteristics_id' => 'id']);
    }
}
