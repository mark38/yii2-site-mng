<?php

namespace common\models\broadcast;

use Yii;

/**
 * This is the model class for table "broadcast_send".
 *
 * @property integer $id
 * @property integer $broadcast_id
 * @property integer $created_at
 * @property integer $status
 *
 * @property BroadcastAddress[] $broadcastAddresses
 * @property Broadcast $broadcast
 */
class BroadcastSend extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'broadcast_send';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['broadcast_id', 'created_at', 'status'], 'integer'],
            [['broadcast_id'], 'exist', 'skipOnError' => true, 'targetClass' => Broadcast::className(), 'targetAttribute' => ['broadcast_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'broadcast_id' => 'Broadcast ID',
            'created_at' => 'Created At',
            'status' => 'Status'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBroadcastAddresses()
    {
        return $this->hasMany(BroadcastAddress::className(), ['broadcast_send_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBroadcast()
    {
        return $this->hasOne(Broadcast::className(), ['id' => 'broadcast_id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->created_at = time();
        }

        return true;
    }
}
