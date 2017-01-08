<?php

namespace common\models\sms;

use Yii;

/**
 * This is the model class for table "sms_send".
 *
 * @property integer $id
 * @property integer $sms_content_id
 * @property integer $created_at
 * @property integer $status
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
            [['sms_content_id', 'created_at', 'status'], 'integer'],
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
            'created_at' => 'Created Ad',
            'status' => 'Status',
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
        if ($insert) {
            $this->created_at = time();
        }

        return true;
    }
}
