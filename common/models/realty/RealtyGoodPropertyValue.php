<?php

namespace common\models\realty;

use Yii;

/**
 * This is the model class for table "realty_good_property_value".
 *
 * @property integer $id
 * @property integer $realty_goods_id
 * @property integer $realty_property_values_id
 * @property string $name
 *
 * @property RealtyGoods $realtyGoods
 * @property RealtyPropertyValues $realtyPropertyValues
 */
class RealtyGoodPropertyValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'realty_good_property_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['realty_goods_id', 'realty_property_values_id'], 'integer'],
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
            'realty_goods_id' => 'Realty Goods ID',
            'realty_property_values_id' => 'Realty Property Values ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRealtyGoods()
    {
        return $this->hasOne(RealtyGoods::className(), ['id' => 'realty_goods_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRealtyPropertyValues()
    {
        return $this->hasOne(RealtyPropertyValues::className(), ['id' => 'realty_property_values_id']);
    }
}
