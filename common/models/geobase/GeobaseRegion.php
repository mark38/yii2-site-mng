<?php

namespace common\models\geobase;

use Yii;

/**
 * This is the model class for table "geobase_region".
 *
 * @property string $id
 * @property string $name
 *
 * @property TorAds[] $torAds
 */
class GeobaseRegion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'geobase_region';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name'], 'required'],
            [['id'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['id'], 'unique']
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTorAds()
    {
        return $this->hasMany(TorAds::className(), ['region_id' => 'id']);
    }
}
