<?php

namespace common\models\shop;

use Yii;

/**
 * This is the model class for table "shop_properties".
 *
 * @property integer $id
 * @property string $verification_code
 * @property string $name
 * @property string $anchor
 * @property string $url
 * @property integer $seq
 * @property integer $state
 * @property string $unit
 * @property integer $number
 */
class ShopProperties extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_properties';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['seq', 'state', 'number'], 'integer'],
            [['verification_code', 'name', 'anchor', 'url'], 'string', 'max' => 255],
            [['unit'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'verification_code' => 'Код',
            'name' => 'Наименование',
            'anchor' => 'Наименование на сайте',
            'url' => 'Имя латиницей',
            'seq' => 'Порядковый номер',
            'range' => 'Диапазон',
            'state' => 'Состояние',
            'unit' => 'Еденица измерения',
            'number' => 'Числовое значение',
        ];
    }

    public static function findLastSequence()
    {
        $q = static::find()->orderBy(['seq' => SORT_DESC])->one();
        return ($q ? $q->seq : 0);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (!$this->seq) {
                $property = self::find()->orderBy(['seq' => SORT_DESC])->one();
                $this->seq = $property->seq + 1;
            }
            return true;
        }

        return true;
    }

    public function afterDelete()
    {
        $properties = self::find()->orderBy(['seq' => SORT_ASC])->all();
        if ($properties) {
            foreach ($properties as $seq => $property) {
                $property->seq = $seq + 1;
                $property->update();
            }
        }

        return true;
    }
}
