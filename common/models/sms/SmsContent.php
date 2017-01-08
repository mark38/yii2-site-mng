<?php

namespace common\models\sms;

use Yii;

/**
 * This is the model class for table "sms_content".
 *
 * @property integer $id
 * @property string $content
 * @property integer $created_at
 * @property string $comment
 * @property integer $contact_send
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
            [['created_at', 'contact_send'], 'integer'],
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
            'content' => 'Текст SMS',
            'created_at' => 'Created At',
            'comment' => 'Комменттарий',
            'contact_send' => 'Contact Send'
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
        if (parent::beforeSave($insert)) {
            $this->created_at = time();
        }

        return true;
    }
}
