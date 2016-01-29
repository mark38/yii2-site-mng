<?php

namespace common\models\sh;

use Yii;

/**
 * This is the model class for table "mod_sh_price_item".
 *
 * @property integer $id
 * @property integer $price_types_id
 * @property integer $items_id
 * @property string $price
 *
 * @property ShPriceTypes $priceType
 * @property ShItems $item
 */
class ShPriceItem extends \yii\db\ActiveRecord
{
    public $min_price;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mod_sh_price_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price_types_id', 'items_id'], 'integer'],
            [['price'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'price_types_id' => 'Price Types ID',
            'items_id' => 'Items ID',
            'price' => 'Price',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPriceType()
    {
        return $this->hasOne(ShPriceTypes::className(), ['id' => 'price_types_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(ShItems::className(), ['id' => 'items_id']);
    }
}
