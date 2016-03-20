<?php

namespace common\models\realty;

use Yii;

/**
 * This is the model class for table "realty_properties".
 *
 * @property integer $id
 * @property integer $realty_property_types_id
 * @property string $name
 * @property string $url
 * @property integer $seq
 *
 * @property RealtyPropertyTypes $realtyPropertyTypes
 */
class RealtyProperties extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'realty_properties';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['realty_property_types_id', 'seq'], 'integer'],
            [['name', 'url'], 'string', 'max' => 255],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'realty_property_types_id' => 'Realty Property Types ID',
            'name' => 'Name',
            'url' => 'Url',
            'seq' => 'Seq',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRealtyPropertyType()
    {
        return $this->hasOne(RealtyPropertyTypes::className(), ['id' => 'realty_property_types_id']);
    }
}
