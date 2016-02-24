<?php

namespace common\models\sh;

use Yii;

/**
 * This is the model class for table "mod_sh_item_characteristics".
 *
 * @property integer $id
 * @property integer $items_id
 * @property integer $characteristics_id
 * @property string $value
 * @property integer $state
 *
 * @property ShItems $items
 * @property ShCharacteristics $characteristic
 */
class ShItemCharacteristics extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mod_sh_item_characteristics';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['items_id', 'characteristics_id', 'state'], 'integer'],
            [['value'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'items_id' => 'Items ID',
            'characteristics_id' => 'Characteristics ID',
            'value' => 'Value',
            'state' => 'State',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(ShItems::className(), ['id' => 'items_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCharacteristic()
    {
        return $this->hasOne(ShCharacteristics::className(), ['id' => 'characteristics_id']);
    }
}
