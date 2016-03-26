<?php

namespace common\models\main;

use Yii;

/**
 * This is the model class for table "modules".
 *
 * @property integer $id
 * @property string $name
 * @property string $url
 * @property integer $visible
 * @property string $icon
 * @property integer $seq
 */
class Modules extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'modules';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['visible', 'seq'], 'integer'],
            [['name', 'url', 'icon'], 'string', 'max' => 255],
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
            'url' => 'Url',
            'visible' => 'Visible',
            'icon' => 'Icon',
            'seq' => 'Seq',
        ];
    }
}
