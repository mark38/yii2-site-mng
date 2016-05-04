<?php

namespace common\models\bus;

use Yii;

/**
 * This is the model class for table "rooms".
 *
 * @property integer $id
 * @property string $comment
 * @property integer $comfort_id
 * @property integer $room_type_id
 * @property integer $room_climate_id
 * @property string $rigid_block
 * @property integer $hotel_id
 * @property string $number
 * @property integer $floors_id
 * @property integer $additional_place
 * @property integer $place_id
 * @property string $adult_additional_place
 *
 * @property BookActive[] $bookActives
 * @property Books[] $books
 * @property BooksBlock[] $booksBlocks
 * @property Price[] $prices
 * @property Comfort $comfort
 * @property RoomType $roomType
 * @property RoomClimate $roomClimate
 * @property Hotel $hotel
 * @property Floors $floors
 * @property Place $place
 * @property RoomsService[] $roomsServices
 */
class Rooms extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rooms';
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
            [['comment'], 'string'],
            [['comfort_id', 'room_type_id', 'room_climate_id', 'hotel_id', 'floors_id', 'additional_place', 'place_id'], 'integer'],
            [['rigid_block', 'adult_additional_place'], 'string', 'max' => 1],
            [['number'], 'string', 'max' => 256],
            [['comfort_id'], 'exist', 'skipOnError' => true, 'targetClass' => Comfort::className(), 'targetAttribute' => ['comfort_id' => 'id']],
            [['room_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => RoomType::className(), 'targetAttribute' => ['room_type_id' => 'id']],
            [['room_climate_id'], 'exist', 'skipOnError' => true, 'targetClass' => RoomClimate::className(), 'targetAttribute' => ['room_climate_id' => 'id']],
            [['hotel_id'], 'exist', 'skipOnError' => true, 'targetClass' => Hotel::className(), 'targetAttribute' => ['hotel_id' => 'id']],
            [['floors_id'], 'exist', 'skipOnError' => true, 'targetClass' => Floors::className(), 'targetAttribute' => ['floors_id' => 'id']],
            [['place_id'], 'exist', 'skipOnError' => true, 'targetClass' => Place::className(), 'targetAttribute' => ['place_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'comment' => 'Comment',
            'comfort_id' => 'Comfort ID',
            'room_type_id' => 'Room Type ID',
            'room_climate_id' => 'Room Climate ID',
            'rigid_block' => 'Rigid Block',
            'hotel_id' => 'Hotel ID',
            'number' => 'Number',
            'floors_id' => 'Floors ID',
            'additional_place' => 'Additional Place',
            'place_id' => 'Place ID',
            'adult_additional_place' => 'Adult Additional Place',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookActives()
    {
        return $this->hasMany(BookActive::className(), ['rooms_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBooks()
    {
        return $this->hasMany(Books::className(), ['rooms_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBooksBlocks()
    {
        return $this->hasMany(BooksBlock::className(), ['rooms_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrices()
    {
        return $this->hasMany(Price::className(), ['rooms_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComfort()
    {
        return $this->hasOne(Comfort::className(), ['id' => 'comfort_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoomType()
    {
        return $this->hasOne(RoomType::className(), ['id' => 'room_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoomClimate()
    {
        return $this->hasOne(RoomClimate::className(), ['id' => 'room_climate_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHotel()
    {
        return $this->hasOne(Hotel::className(), ['id' => 'hotel_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFloors()
    {
        return $this->hasOne(Floors::className(), ['id' => 'floors_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlace()
    {
        return $this->hasOne(Place::className(), ['id' => 'place_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoomsServices()
    {
        return $this->hasMany(RoomsService::className(), ['rooms_id' => 'id']);
    }
}
