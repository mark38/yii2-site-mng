<?php

namespace common\models\bus;

use Yii;

/**
 * This is the model class for table "room_type".
 *
 * @property integer $id
 * @property string $room_type
 * @property string $room_type_default
 *
 * @property Rooms[] $rooms
 */
class RoomType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'room_type';
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
            [['room_type'], 'string', 'max' => 256],
            [['room_type_default'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'room_type' => 'Room Type',
            'room_type_default' => 'Room Type Default',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRooms()
    {
        return $this->hasMany(Rooms::className(), ['room_type_id' => 'id']);
    }
}
