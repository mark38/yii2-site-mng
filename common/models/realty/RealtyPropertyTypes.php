<?php

namespace common\models\realty;

use Yii;

/**
 * This is the model class for table "realty_property_types".
 *
 * @property integer $id
 * @property string $name
 * @property string $comment
 *
 * @property RealtyProperties[] $realtyProperties
 */
class RealtyPropertyTypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'realty_property_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'comment'], 'string', 'max' => 255]
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
            'comment' => 'Comment',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRealtyProperties()
    {
        return $this->hasMany(RealtyProperties::className(), ['realty_property_types_id' => 'id']);
    }
}
