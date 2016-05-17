<?php

namespace common\models\certificates;

use Yii;

/**
 * This is the model class for table "certificates".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property integer $state
 */
class Certificates extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'certificates';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['state'], 'integer'],
            [['code', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Код',
            'name' => 'Название',
            'state' => 'Статус',
        ];
    }
}
