<?php

namespace common\models\gallery;

use Imagine\Imagick\Image;
use Yii;

/**
 * This is the model class for table "{{%gallery_images}}".
 *
 * @property integer $id
 * @property integer $gallery_groups_id
 * @property string $small
 * @property string $large
 * @property string $name
 * @property string $alt
 * @property string $url
 * @property integer $seq
 * @property string picture
 *
 * @property GalleryGroups[] $galleryGroups
 * @property GalleryGroups $galleryGroup
 */
class GalleryImages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gallery_images}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gallery_groups_id', 'seq'], 'integer'],
            [['small', 'large', 'name', 'alt', 'url'], 'string', 'max' => 255],
            [['picture'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gallery_groups_id' => 'Gallery Groups ID',
            'small' => 'Small',
            'large' => 'Large',
            'name' => 'Name',
            'alt' => 'Alt',
            'url' => 'Url',
            'seq' => 'Seq',
            'picture' => 'Json формат списка изображений',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGalleryGroups()
    {
        return $this->hasMany(GalleryGroups::className(), ['gallery_images_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGalleryGroup()
    {
        return $this->hasOne(GalleryGroups::className(), ['id' => 'gallery_groups_id']);
    }

    function resizeAndConvertImageWebP($width, $height, $density, $originalFilepath, $resizedFilepath)
    {
        $image = new Image();
        $image->ge
        $image = new \Imagick($originalFilepath);
        $origImageDimens = $image->getImageGeometry();
    }
}
