<?php

namespace common\models\shop;

use Yii;

/**
 * This is the model class for table "shop_units".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 *
 * @property ShopGoods[] $shopGoods
 */
class ShopUnits extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_units';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 35],
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
            'code' => 'Code',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopGoods()
    {
        return $this->hasMany(ShopGoods::className(), ['shop_units_id' => 'id']);
    }
}
