<?php

namespace common\models\main;

use Yii;

/**
 * This is the model class for table "layouts".
 *
 * @property integer $id
 * @property string $layout
 *
 * @property Links[] $links
 */
class Layouts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'layouts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['layout'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'layout' => 'Layout',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinks()
    {
        return $this->hasMany(Links::className(), ['layouts_id' => 'id']);
    }
}
