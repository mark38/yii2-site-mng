<?php

namespace common\models\sms;

use Yii;

/**
 * This is the model class for table "sms_send_contacts".
 *
 * @property integer $id
 * @property integer $sms_send_id
 * @property integer $sms_contacts_id
 * @property integer $status
 *
 * @property SmsContacts $smsContact
 * @property SmsSend $smsSend
 */
class SmsSendContacts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sms_send_contacts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sms_send_id', 'sms_contacts_id', 'status'], 'integer'],
            [['sms_contacts_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmsContacts::className(), 'targetAttribute' => ['sms_contacts_id' => 'id']],
            [['sms_send_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmsSend::className(), 'targetAttribute' => ['sms_send_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sms_send_id' => 'Sms Send ID',
            'sms_contacts_id' => 'Sms Contacts ID',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSmsContact()
    {
        return $this->hasOne(SmsContacts::className(), ['id' => 'sms_contacts_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSmsSend()
    {
        return $this->hasOne(SmsSend::className(), ['id' => 'sms_send_id']);
    }
}
