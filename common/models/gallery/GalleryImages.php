<?php

namespace common\models\gallery;

use Yii;
use tpmanc\imagick\Imagick;
use WebPConvert\WebPConvert;

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

    public function getGalleryType()
    {
        return $this->hasOne(GalleryTypes::className(), ['id' => 'gallery_types_id'])->via('galleryGroup');
    }

    /**
     * @param $width
     * @param $height
     * @param $originalFilePath
     * @param $resizedPath
     * @param $fileName
     * @param $extension
     * Для работы необохима установка ImageMagick и WebP
     */
    function resizeAndConvertImageWebP($width, $height, $originalFilePath, $resizedPath, $fileName, $extension)
    {
        $resizedPath = preg_replace('/\/$/', '', $resizedPath);

        $image = Imagick::open($originalFilePath);
        $image->resize($width, $height)->saveTo($resizedPath.'/'.$fileName.'.'.$extension);
        WebPConvert::convert($resizedPath.'/'.$fileName.'.'.$extension, $resizedPath.'/'.$fileName.'.webp');
    }
}
