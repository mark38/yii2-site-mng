<?php

namespace common\models\realty;

use Yii;

/**
 * This is the model class for table "realty_property_values".
 *
 * @property integer $id
 * @property integer $realty_properties_id
 * @property string $name
 * @property string $url
 *
 * @property RealtyProperties $realtyProperties
 */
class RealtyPropertyValues extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'realty_property_values';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['realty_properties_id'], 'integer'],
            [['name', 'url'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'realty_properties_id' => 'Realty Properties ID',
            'name' => 'Name',
            'url' => 'Url',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRealtyProperties()
    {
        return $this->hasOne(RealtyProperties::className(), ['id' => 'realty_properties_id']);
    }
}
