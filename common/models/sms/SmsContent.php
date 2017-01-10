<?php

namespace common\models\sms;

use Yii;

/**
 * This is the model class for table "sms_content".
 *
 * @property integer $id
 * @property string $content
 * @property string $comment
 * @property integer $contact_send
 * @property integer $created_at
 *
 * @property SmsSend[] $smsSends
 */
class SmsContent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sms_content';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['contact_send', 'created_at'], 'integer'],
            [['comment'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Текст SMS-сообщения',
            'comment' => 'Комментарий',
            'contact_send' => 'Contact Send',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSmsSends()
    {
        return $this->hasMany(SmsSend::className(), ['sms_content_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->created_at = time();
        }

        return true;
    }
}
