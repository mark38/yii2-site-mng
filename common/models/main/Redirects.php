<?php

namespace common\models\main;

use Yii;

/**
 * This is the model class for table "redirects".
 *
 * @property integer $id
 * @property integer $links_id
 * @property string $url
 *
 * @property Links $links
 */
class Redirects extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'redirects';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['links_id'], 'integer'],
            [['url'], 'string', 'max' => 1024]
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
            'url' => 'Url',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLink()
    {
        return $this->hasOne(Links::className(), ['id' => 'links_id']);
    }
}
