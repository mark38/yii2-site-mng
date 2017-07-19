<?php

namespace common\models\forms;

use Yii;
use common\models\main\Emails;

/**
 * This is the model class for table "form_emails_notice".
 *
 * @property integer $id
 * @property integer $form_types_id
 * @property integer $emails_id
 *
 * @property Emails $email
 * @property FormTypes $formTypes
 */
class FormEmailsNotice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'form_emails_notice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['form_types_id', 'emails_id'], 'integer'],
            [['emails_id'], 'exist', 'skipOnError' => true, 'targetClass' => Emails::className(), 'targetAttribute' => ['emails_id' => 'id']],
            [['form_types_id'], 'exist', 'skipOnError' => true, 'targetClass' => FormTypes::className(), 'targetAttribute' => ['form_types_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'form_types_id' => 'Form Types ID',
            'emails_id' => 'Emails ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmail()
    {
        return $this->hasOne(Emails::className(), ['id' => 'emails_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFormTypes()
    {
        return $this->hasOne(FormTypes::className(), ['id' => 'form_types_id']);
    }
}
