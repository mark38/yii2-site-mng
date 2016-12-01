<?php

namespace common\models\main;

use Yii;
use common\models\User;

/**
 * This is the model class for table "ratings".
 *
 * @property integer $id
 * @property integer $links_id
 * @property integer $user_id
 * @property integer $sessions_id
 * @property integer $value
 *
 * @property Links $links
 * @property Sessions $sessions
 * @property User $user
 */
class Ratings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ratings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['links_id', 'user_id', 'sessions_id', 'value'], 'integer'],
            [['links_id'], 'exist', 'skipOnError' => true, 'targetClass' => Links::className(), 'targetAttribute' => ['links_id' => 'id']],
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
            'links_id' => 'Links ID',
            'user_id' => 'User ID',
            'sessions_id' => 'Sessions ID',
            'value' => 'Value',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinks()
    {
        return $this->hasOne(Links::className(), ['id' => 'links_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSessions()
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
