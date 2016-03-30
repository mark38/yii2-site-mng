<?php

namespace common\models\bus;

use Yii;

/**
 * This is the model class for table "tours".
 *
 * @property integer $id
 * @property integer $hotel_id
 * @property integer $days
 * @property string $day_go
 * @property string $day_back
 * @property integer $buses_id_go
 * @property integer $buses_id_back
 * @property string $day_week_go
 *
 * @property BookActive[] $bookActives
 * @property BookTours[] $bookTours
 * @property BooksBlock[] $booksBlocks
 * @property Price[] $prices
 * @property Hotel $hotel
 * @property Buses $busesIdGo
 * @property Buses $busesIdBack
 */
class Tours extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tours';
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
            [['hotel_id', 'days', 'buses_id_go', 'buses_id_back'], 'integer'],
            [['day_go', 'day_back'], 'safe'],
            [['day_week_go'], 'string', 'max' => 4],
            [['hotel_id'], 'exist', 'skipOnError' => true, 'targetClass' => Hotel::className(), 'targetAttribute' => ['hotel_id' => 'id']],
            [['buses_id_go'], 'exist', 'skipOnError' => true, 'targetClass' => Buses::className(), 'targetAttribute' => ['buses_id_go' => 'id']],
            [['buses_id_back'], 'exist', 'skipOnError' => true, 'targetClass' => Buses::className(), 'targetAttribute' => ['buses_id_back' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hotel_id' => 'Hotel ID',
            'days' => 'Days',
            'day_go' => 'Day Go',
            'day_back' => 'Day Back',
            'buses_id_go' => 'Buses Id Go',
            'buses_id_back' => 'Buses Id Back',
            'day_week_go' => 'Day Week Go',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookActives()
    {
        return $this->hasMany(BookActive::className(), ['tours_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookTours()
    {
        return $this->hasMany(BookTours::className(), ['tours_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBooksBlocks()
    {
        return $this->hasMany(BooksBlock::className(), ['tours_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrices()
    {
        return $this->hasMany(Price::className(), ['tours_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHotel()
    {
        return $this->hasOne(Hotel::className(), ['id' => 'hotel_id']);
    }

    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id'])->via('hotel');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusesIdGo()
    {
        return $this->hasOne(Buses::className(), ['id' => 'buses_id_go']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusesIdBack()
    {
        return $this->hasOne(Buses::className(), ['id' => 'buses_id_back']);
    }
}
