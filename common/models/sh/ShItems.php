<?php

namespace common\models\sh;

use Yii;

/**
 * This is the model class for table "mod_sh_items".
 *
 * @property integer $id
 * @property integer $goods_id
 * @property string $code
 * @property integer $state
 *
 * @property ShGoods $goods
 */
class ShItems extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mod_sh_items';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'state'], 'integer'],
            [['code'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id' => 'Goods ID',
            'code' => 'Code',
            'state' => 'State',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasOne(ShGoods::className(), ['id' => 'goods_id']);
    }

    public function getPrice()
    {
        return $this->hasOne(ShPriceItem::className(), ['items_id' => 'id']);
    }
}
