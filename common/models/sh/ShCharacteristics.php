<?php

namespace common\models\sh;

use Yii;

/**
 * This is the model class for table "mod_sh_characteristics".
 *
 * @property integer $id
 * @property string $characteristic
 *
 * @property ShItemCharacteristics[] $modShItemCharacteristics
 */
class ShCharacteristics extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mod_sh_characteristics';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['characteristic'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'characteristic' => 'Characteristic',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShItemCharacteristics()
    {
        return $this->hasMany(ShItemCharacteristics::className(), ['characteristics_id' => 'id']);
    }
}
