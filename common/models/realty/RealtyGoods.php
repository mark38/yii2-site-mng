<?php

namespace common\models\realty;

use common\models\gallery\GalleryGroups;
use common\models\main\Links;
use Yii;

/**
 * This is the model class for table "realty_goods".
 *
 * @property integer $id
 * @property integer $realty_groups_id
 * @property integer $hot
 * @property string $name
 * @property string $address
 * @property string $price
 * @property integer $gallery_groups_id
 * @property integer $square
 * @property string $text
 * @property string $coords
 * @property string $created_at
 *
 * @property RealtyGoodPropertyValue[] $realtyGoodPropertyValues
 * @property GalleryGroups $galleryGroups
 * @property RealtyGroups $realtyGroups
 */
class RealtyGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'realty_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['realty_groups_id', 'hot', 'gallery_groups_id', 'square', 'created_at'], 'integer'],
            [['price'], 'number'],
            [['name', 'address', 'text'], 'string'],
            [['coords'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'realty_groups_id' => 'Группа объявлений',
            'hot' => 'Горячее предложение',
            'name' => 'Наименование (Заголовок)',
            'address' => 'Адрес',
            'price' => 'Стоимость',
            'gallery_groups_id' => 'Фотографии объекта',
            'square' => 'Площадь',
            'text' => 'Полное описание',
            'coords' => 'Координаты',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRealtyGoodPropertyValues()
    {
        return $this->hasMany(RealtyGoodPropertyValue::className(), ['realty_goods_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGalleryGroup()
    {
        return $this->hasOne(GalleryGroups::className(), ['id' => 'gallery_groups_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRealtyGroup()
    {
        return $this->hasOne(RealtyGroups::className(), ['id' => 'realty_groups_id']);
    }

    public function getLink()
    {
        return $this->hasOne(Links::className(), ['id' => 'links_id'])->via('realtyGroup');
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->created_at = time();

            return true;
        } else {
            return true;
        }
    }
}
