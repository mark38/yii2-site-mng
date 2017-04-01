<?php

namespace common\models\forms;

use Yii;

/**
 * This is the model class for table "form_fields".
 *
 * @property integer $id
 * @property integer $form_types_id
 * @property string $name
 * @property integer $visible
 *
 * @property FormTypes $formTypes
 */
class FormFields extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'form_fields';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['form_types_id', 'visible'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['form_types_id'], 'exist', 'skipOnError' => true, 'targetClass' => FormTypes::className(), 'targetAttribute' => ['form_types_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'form_types_id' => 'Form Types ID',
            'name' => 'Name',
            'visible' => 'Visible',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFormTypes()
    {
        return $this->hasOne(FormTypes::className(), ['id' => 'form_types_id']);
    }
}
