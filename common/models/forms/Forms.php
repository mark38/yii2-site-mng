<?php

namespace common\models\forms;

use Yii;
use common\models\main\Sessions;
use common\models\User;

/**
 * This is the model class for table "forms".
 *
 * @property integer $id
 * @property integer $form_types_id
 * @property integer $user_id
 * @property integer $sessions_id
 * @property string $fio
 * @property string $phone
 * @property string $email
 * @property string $comment
 * @property integer $form_select1_id
 *
 * @property FormSelect $formSelect1
 * @property FormTypes $formType
 * @property Sessions $session
 * @property User $user
 */
class Forms extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'forms';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['form_types_id', 'user_id', 'sessions_id', 'form_select1_id'], 'integer'],
            [['fio', 'phone', 'email'], 'string', 'max' => 255],
            [['comment'], 'string', 'max' => 2048],
            [['form_select1_id'], 'exist', 'skipOnError' => true, 'targetClass' => FormSelect::className(), 'targetAttribute' => ['form_select1_id' => 'id']],
            [['form_types_id'], 'exist', 'skipOnError' => true, 'targetClass' => FormTypes::className(), 'targetAttribute' => ['form_types_id' => 'id']],
            [['sessions_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sessions::className(), 'targetAttribute' => ['sessions_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'user_id' => 'User ID',
            'sessions_id' => 'Sessions ID',
            'fio' => 'Fio',
            'phone' => 'Phone',
            'email' => 'Email',
            'comment' => 'Comment',
            'form_select1_id' => 'Form Select1 ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFormSelect1()
    {
        return $this->hasOne(FormSelect::className(), ['id' => 'form_select1_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFormType()
    {
        return $this->hasOne(FormTypes::className(), ['id' => 'form_types_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSession()
    {
        return $this->hasOne(Sessions::className(), ['id' => 'sessions_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
