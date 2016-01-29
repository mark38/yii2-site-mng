<?php

namespace common\models\clb;

use Yii;

/**
 * This is the model class for table "mod_clb_list".
 *
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property integer $created_at
 */
class ClbList extends \yii\db\ActiveRecord
{
    public $params = [':name' => 'test'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mod_clb_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'phone'], 'required'],
            [['created_at'], 'integer'],
            [['name', 'phone'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Ваше имя',
            'phone' => 'Контактный телефон',
            'created_at' => 'Created At',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->created_at = time();
            return true;
        }
    }
}
