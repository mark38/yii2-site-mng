<?php

namespace common\models\gallery;

use Yii;

/**
 * This is the model class for table "{{%gallery_groups}}".
 *
 * @property integer $id
 * @property integer $gallery_types_id
 * @property integer $gallery_images_id
 * @property string $name
 *
 * @property GalleryImages $galleryImages
 * @property GalleryTypes $galleryTypes
 * @property GalleryImages[] $galleryImages0
 */
class GalleryGroups extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gallery_groups}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gallery_types_id', 'gallery_images_id'], 'integer'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gallery_types_id' => 'Gallery Types ID',
            'gallery_images_id' => 'Gallery Images ID',
            'name' => 'Наименование группы',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGalleryImage()
    {
        return $this->hasOne(GalleryImages::className(), ['id' => 'gallery_images_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGalleryType()
    {
        return $this->hasOne(GalleryTypes::className(), ['id' => 'gallery_types_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGalleryImages()
    {
        return $this->hasMany(GalleryImages::className(), ['gallery_groups_id' => 'id']);
    }
}
