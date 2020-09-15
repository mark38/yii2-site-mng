<?php

namespace common\models\bus;

use Yii;

/**
 * This is the model class for table "price".
 *
 * @property integer $id
 * @property integer $tours_id
 * @property integer $rooms_id
 * @property integer $client_type_id
 * @property integer $price
 * @property integer $sb_price
 *
 * @property Tours $tour
 * @property Rooms $rooms
 * @property ClientType $clientType
 * @property Hotel $hotel
 * @property City $city
 */
class Price extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'price';
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
            [['tours_id', 'rooms_id', 'client_type_id', 'price', 'sb_price'], 'integer'],
            [['tours_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tours::className(), 'targetAttribute' => ['tours_id' => 'id']],
            [['rooms_id'], 'exist', 'skipOnError' => true, 'targetClass' => Rooms::className(), 'targetAttribute' => ['rooms_id' => 'id']],
            [['client_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ClientType::className(), 'targetAttribute' => ['client_type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tours_id' => 'Tours ID',
            'rooms_id' => 'Rooms ID',
            'client_type_id' => 'Client Type ID',
            'price' => 'Price',
            'sb_price' => 'Sb Price',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTour()
    {
        return $this->hasOne(Tours::className(), ['id' => 'tours_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHotel()
    {
        return $this->hasOne(Hotel::className(), ['id' => 'hotel_id'])->via('tour');
    }

    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id'])->via('hotel');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoom()
    {
        return $this->hasOne(Rooms::className(), ['id' => 'rooms_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientType()
    {
        return $this->hasOne(ClientType::className(), ['id' => 'client_type_id']);
    }
}
