<?php

namespace common\models\sh;

use Yii;

/**
 * This is the model class for table "mod_sh_price_good".
 *
 * @property integer $id
 * @property integer $price_types_id
 * @property integer $goods_id
 * @property string $price
 *
 * @property ShPriceTypes $priceType
 * @property ShGoods $good
 */
class ShPriceGood extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mod_sh_price_good';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price_types_id', 'goods_id'], 'integer'],
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
            'goods_id' => 'Goods ID',
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
    public function getGood()
    {
        return $this->hasOne(ShGoods::className(), ['id' => 'goods_id']);
    }
}
