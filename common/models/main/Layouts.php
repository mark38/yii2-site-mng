<?php

namespace common\models\main;

use Yii;

/**
 * This is the model class for table "layouts".
 *
 * @property integer $id
 * @property string $name
 * @property string $comment
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
            [['name', 'comment'], 'string', 'max' => 255],
            [['name'], 'unique']
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
            'comment' => 'Comment',
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
