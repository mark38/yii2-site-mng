<?php

namespace common\models\certificates;

use Yii;

/**
 * This is the model class for table "tasks".
 *
 * @property integer $id
 * @property integer $created_at
 * @property integer $state
 *
 * @property Requests[] $requests
 */
class Tasks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'certificates_tasks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'state'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Дата создания',
            'state' => 'Состояние',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequests()
    {
        return $this->hasMany(Requests::className(), ['tasks_id' => 'id']);
    }

    public function getRequestAmount()
    {
        return Requests::find()->where(['tasks_id' => $this->id])->count();
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->created_at = time();
        }
        return true;
    }
}
