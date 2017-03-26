<?php

namespace common\models\forms;

use Yii;

/**
 * This is the model class for table "form_types".
 *
 * @property integer $id
 * @property string $name
 * @property string $select1_name
 *
 * @property FormSelect[] $formSelects
 * @property Forms[] $forms
 */
class FormTypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'form_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'select1_name'], 'string', 'max' => 255],
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
            'select1_name' => 'Select1 Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFormSelects()
    {
        return $this->hasMany(FormSelect::className(), ['form_types_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForms()
    {
        return $this->hasMany(Forms::className(), ['form_types_id' => 'id']);
    }
}
