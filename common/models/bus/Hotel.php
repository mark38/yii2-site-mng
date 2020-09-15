<?php

namespace common\models\bus;

use Yii;

/**
 * This is the model class for table "hotel".
 *
 * @property integer $id
 * @property string $hotel
 * @property integer $city_id
 * @property string $content
 * @property integer $sort_num
 * @property integer $hotel_active
 *
 * @property City $city
 * @property HotelImages[] $hotelImages
 * @property Rooms[] $rooms
 * @property Tours[] $tours
 */
class Hotel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hotel';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('bus');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city_id', 'sort_num', 'hotel_active'], 'integer'],
            [['content'], 'string'],
            [['hotel'], 'string', 'max' => 512],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hotel' => 'Hotel',
            'city_id' => 'City ID',
            'content' => 'Content',
            'sort_num' => 'Sort Num',
            'hotel_active' => 'Hotel Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHotelImages()
    {
        return $this->hasMany(HotelImages::className(), ['hotel_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRooms()
    {
        return $this->hasMany(Rooms::className(), ['hotel_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTours()
    {
        return $this->hasMany(Tours::className(), ['hotel_id' => 'id']);
    }
}
