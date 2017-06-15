<?php

namespace common\models\main;

use Yii;

/**
 * This is the model class for table "emails".
 *
 * @property integer $id
 * @property string $name
 * @property integer $state
 *
 * @property FormEmailsNotice[] $formEmailsNotices
 */
class Emails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'emails';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['state'], 'integer'],
            [['name'], 'string', 'max' => 255],
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
            'state' => 'State',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFormEmailsNotices()
    {
        return $this->hasMany(FormEmailsNotice::className(), ['emails_id' => 'id']);
    }
}
