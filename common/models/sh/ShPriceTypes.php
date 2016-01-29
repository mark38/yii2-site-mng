<?php

namespace common\models\sh;

use Yii;

/**
 * This is the model class for table "mod_sh_price_types".
 *
 * @property integer $id
 * @property string $code
 * @property string $type
 * @property integer $def
 */
class ShPriceTypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mod_sh_price_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['def'], 'integer'],
            [['code', 'type'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'type' => 'Type',
            'def' => 'Def',
        ];
    }
}
