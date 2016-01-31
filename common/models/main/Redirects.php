<?php

namespace common\models\main;

use Yii;

/**
 * This is the model class for table "redirects".
 *
 * @property integer $id
 * @property string $links_id
 * @property string $url
 * @property integer $code
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
            [['links_id', 'code'], 'integer'],
            [['url'], 'string', 'max' => 255]
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
            'code' => 'Code',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinks()
    {
        return $this->hasOne(Links::className(), ['id' => 'links_id']);
    }
}
