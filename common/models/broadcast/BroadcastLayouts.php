<?php

namespace common\models\broadcast;

use Yii;

/**
 * This is the model class for table "broadcast_layouts".
 *
 * @property integer $id
 * @property string $name
 * @property string $layout_path
 * @property string $content
 *
 * @property Broadcast[] $broadcasts
 */
class BroadcastLayouts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'broadcast_layouts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'layout_path'], 'required'],
            [['content'], 'string'],
            [['name', 'layout_path'], 'string', 'max' => 255],
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
            'layout_path' => 'Layout Path',
            'content' => 'Content',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBroadcasts()
    {
        return $this->hasMany(Broadcast::className(), ['broadcast_layouts_id' => 'id']);
    }
}
