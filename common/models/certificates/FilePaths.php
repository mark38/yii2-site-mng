<?php

namespace common\models\certificates;

use Yii;

/**
 * This is the model class for table "file_paths".
 *
 * @property integer $id
 * @property string $name
 * @property string $path
 * @property integer $tasks_id
 * @property string $type
 *
 * @property Requests $requests
 */
class FilePaths extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'certificates_file_paths';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tasks_id'], 'integer'],
            [['name', 'path', 'type'], 'string', 'max' => 255],
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
            'name' => 'Name',
            'path' => 'Path',
            'tasks_id' => 'Tasks ID',
            'type' => 'Type'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Tasks::className(), ['id' => 'tasks_id']);
    }
}
