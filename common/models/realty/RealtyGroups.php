<?php

namespace common\models\realty;

use Yii;

/**
 * This is the model class for table "realty_groups".
 *
 * @property integer $id
 * @property string $links_id
 * @property string $name
 *
 * @property Links $links
 */
class RealtyGroups extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'realty_groups';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['links_id'], 'integer'],
            [['name'], 'string', 'max' => 255]
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
            'name' => 'Name',
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
