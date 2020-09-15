<?php

namespace common\models\broadcast;

use Yii;

/**
 * This is the model class for table "broadcast".
 *
 * @property integer $id
 * @property integer $broadcast_layouts_id
 * @property integer $registered_users
 * @property string $destinations
 * @property string $title
 * @property string $h1
 * @property string $content
 * @property integer $created_at
 *
 * @property BraodcastSend[] $braodcastSends
 * @property BroadcastLayouts $broadcastLayouts
 */
class Broadcast extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'broadcast';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['registered_users', 'broadcast_layouts_id', 'created_at'], 'integer'],
            [['content', 'destinations', 'h1'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['broadcast_layouts_id'], 'exist', 'skipOnError' => true, 'targetClass' => BroadcastLayouts::className(), 'targetAttribute' => ['broadcast_layouts_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'registered_users' => 'Доставка зарегистрированным пользователям',
            'broadcast_layouts_id' => 'Шаблон',
            'title' => 'Заголовок сообщения',
            'h1' => 'Заголовок в теле письма',
            'content' => 'Текст письма',
            'created_at' => 'Дата создания',
            'destinations' => 'Список адресов'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBroadcastSends()
    {
        return $this->hasMany(BroadcastSend::className(), ['broadcast_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBroadcastSendAmount()
    {
        return BroadcastSend::find()->where(['broadcast_id' => $this->id, 'status' => 1])->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBroadcastLayout()
    {
        return $this->hasOne(BroadcastLayouts::className(), ['id' => 'broadcast_layouts_id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->created_at = time();
        }

        return true;
    }
}
