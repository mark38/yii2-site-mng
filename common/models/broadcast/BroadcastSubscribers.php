<?php

namespace common\models\broadcast;

use Yii;
use common\models\User;

/**
 * This is the model class for table "broadcast_subscribers".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $email
 * @property string $referral_link
 * @property integer $state
 *
 * @property User $user
 */
class BroadcastSubscribers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'broadcast_subscribers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'state'], 'integer'],
            [['email', 'referral_link'], 'string', 'max' => 255],
            ['email', 'email'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'email' => 'Email',
            'referral_link' => 'Referral Link',
            'state' => 'State',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
