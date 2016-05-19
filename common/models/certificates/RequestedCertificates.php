<?php

namespace common\models\certificates;

use Yii;

/**
 * This is the model class for table "requested_certificates".
 *
 * @property integer $id
 * @property integer $requests_id
 * @property integer $certificates_id
 * @property string $wagons
 *
 * @property Certificates $certificates
 * @property Requests $requests
 */
class RequestedCertificates extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'certificates_requested_certificates';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['requests_id', 'certificates_id'], 'integer'],
            [['wagons'], 'string', 'max' => 1024],
            [['certificates_id'], 'exist', 'skipOnError' => true, 'targetClass' => Certificates::className(), 'targetAttribute' => ['certificates_id' => 'id']],
            [['requests_id'], 'exist', 'skipOnError' => true, 'targetClass' => Requests::className(), 'targetAttribute' => ['requests_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'requests_id' => 'Requests ID',
            'certificates_id' => 'Certificates ID',
            'wagons' => 'Wagons',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCertificate()
    {
        return $this->hasOne(Certificates::className(), ['id' => 'certificates_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequest()
    {
        return $this->hasOne(Requests::className(), ['id' => 'requests_id']);
    }
}
