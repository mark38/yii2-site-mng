<?php

namespace common\models\certificates;

use common\models\main\Companies;
use Yii;

/**
 * This is the model class for table "requests".
 *
 * @property integer $id
 * @property integer $tasks_id
 * @property integer $companies_id
 *
 * @property FilePaths[] $filePaths
 * @property RequestedCertificates[] $requestedCertificates
 * @property Companies $companies
 * @property Tasks $tasks
 */
class Requests extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'certificates_requests';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tasks_id', 'companies_id'], 'integer'],
            [['companies_id'], 'exist', 'skipOnError' => true, 'targetClass' => Companies::className(), 'targetAttribute' => ['companies_id' => 'id']],
            [['tasks_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::className(), 'targetAttribute' => ['tasks_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tasks_id' => 'Задача',
            'companies_id' => 'Компания',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFilePaths()
    {
        return $this->hasMany(FilePaths::className(), ['requests_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequestedCertificates()
    {
        return $this->hasMany(RequestedCertificates::className(), ['requests_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Companies::className(), ['id' => 'companies_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Tasks::className(), ['id' => 'tasks_id']);
    }
}
