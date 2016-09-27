<?php

namespace common\models\broadcast;

use Yii;

/**
 * This is the model class for table "broadcast_files".
 *
 * @property integer $id
 * @property string $name
 * @property string $file
 * @property integer $broadcast_id
 *
 * @property Broadcast $broadcast
 */
class BroadcastFiles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'broadcast_files';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['broadcast_id'], 'integer'],
            [['name', 'file'], 'string', 'max' => 255],
            [['broadcast_id'], 'exist', 'skipOnError' => true, 'targetClass' => Broadcast::className(), 'targetAttribute' => ['broadcast_id' => 'id']],
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
            'file' => 'File',
            'broadcast_id' => 'Broadcast ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBroadcast()
    {
        return $this->hasOne(Broadcast::className(), ['id' => 'broadcast_id']);
    }

    public function afterDelete()
    {

    }
}
