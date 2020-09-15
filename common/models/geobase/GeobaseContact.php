<?php

namespace common\models\geobase;

use Yii;

/**
 * This is the model class for table "geobase_contact".
 *
 * @property integer $id
 * @property integer $geobase_city_id
 * @property integer $city_name
 * @property string $phone
 * @property string $address
 * @property string $working_hours
 * @property integer $def
 * @property integer $seq
 *
 * @property GeobaseCity $geobaseCity
 */
class GeobaseContact extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'geobase_contact';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['geobase_city_id', 'def', 'seq'], 'integer'],
            [['city_name', 'phone', 'address', 'working_hours'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'geobase_city_id' => 'Geobase City ID',
            'phone' => 'Phone',
            'address' => 'Address',
            'working_hours' => 'Working Hours',
            'def' => 'Def',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeobaseCity()
    {
        return $this->hasOne(GeobaseCity::className(), ['id' => 'geobase_city_id'])->orderBy(['name' => SORT_ASC]);
    }
}
