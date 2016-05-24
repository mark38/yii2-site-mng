<?php

namespace common\models\certificates;

use Yii;

/**
 * This is the model class for table "certificates_lost".
 *
 * @property integer $id
 * @property string $wagon
 * @property integer $certificates_id
 * @property integer $tasks_id
 *
 * @property Tasks $tasks
 * @property Certificates $certificates
 */
class CertificatesLost extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'certificates_lost';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['certificates_id', 'tasks_id'], 'integer'],
            [['wagon'], 'string', 'max' => 255],
            [['tasks_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::className(), 'targetAttribute' => ['tasks_id' => 'id']],
            [['certificates_id'], 'exist', 'skipOnError' => true, 'targetClass' => Certificates::className(), 'targetAttribute' => ['certificates_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'wagon' => 'Wagon',
            'certificates_id' => 'Certificates ID',
            'tasks_id' => 'Tasks ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Tasks::className(), ['id' => 'tasks_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCertificate()
    {
        return $this->hasOne(Certificates::className(), ['id' => 'certificates_id']);
    }
}
