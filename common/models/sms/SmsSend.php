<?php

namespace common\models\sms;

use Yii;

/**
 * This is the model class for table "sms_send".
 *
 * @property integer $id
 * @property integer $sms_content_id
 * @property integer $status
 * @property integer $created_at
 *
 * @property SmsContent $smsContent
 * @property SmsSendContacts[] $smsSendContacts
 */
class SmsSend extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sms_send';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sms_content_id', 'status', 'created_at'], 'integer'],
            [['sms_content_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmsContent::className(), 'targetAttribute' => ['sms_content_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sms_content_id' => 'Sms Content ID',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSmsContent()
    {
        return $this->hasOne(SmsContent::className(), ['id' => 'sms_content_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSmsSendContacts()
    {
        return $this->hasMany(SmsSendContacts::className(), ['sms_send_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->created_at = time();
        }

        return true;
    }
}
