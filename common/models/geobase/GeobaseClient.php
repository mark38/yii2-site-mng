<?php

namespace common\models\geobase;

use Yii;
use common\models\main\Sessions;

/**
 * This is the model class for table "geobase_client".
 *
 * @property integer $id
 * @property integer $sessions_id
 * @property integer $contact_id
 *
 * @property Sessions $session
 * @property GeobaseContact $contact
 */
class GeobaseClient extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'geobase_client';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sessions_id', 'contact_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sessions_id' => 'Sessions ID',
            'contact_id' => 'Contact ID',
        ];
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
    public function getContact()
    {
        return $this->hasOne(GeobaseContact::className(), ['id' => 'contact_id']);
    }
}
