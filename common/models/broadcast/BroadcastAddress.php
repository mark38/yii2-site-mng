<?php

namespace common\models\broadcast;

use Yii;
use common\models\User;

/**
 * This is the model class for table "broadcast_address".
 *
 * @property integer $id
 * @property integer $broadcast_send_id
 * @property string $user_id
 * @property string $fio
 * @property string $email
 * @property integer $status
 *
 * @property User $user
 * @property BroadcastSend $broadcastSend
 */
class BroadcastAddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'broadcast_address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['broadcast_send_id', 'user_id', 'status'], 'integer'],
            [['fio', 'email'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['broadcast_send_id'], 'exist', 'skipOnError' => true, 'targetClass' => BroadcastSend::className(), 'targetAttribute' => ['broadcast_send_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'broadcast_send_id' => 'Broadcast Send ID',
            'user_id' => 'User ID',
            'fio' => 'Fio',
            'email' => 'Email',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBroadcastSend()
    {
        return $this->hasOne(BroadcastSend::className(), ['id' => 'broadcast_send_id']);
    }
}
