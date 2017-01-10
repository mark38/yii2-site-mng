<?php

namespace common\models\sms;

use Yii;

/**
 * This is the model class for table "sms_service_params".
 *
 * @property string $service_name
 * @property string $smsru_api_id
 * @property string $smsru_from
 */
class SmsServiceParams extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sms_service_params';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['service_name', 'smsru_api_id', 'smsru_from'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'service_name' => 'Service Name',
            'smsru_api_id' => 'Smsru Api ID',
            'smsru_from' => 'Smsru From',
        ];
    }
}
