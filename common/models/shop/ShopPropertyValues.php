<?php

namespace common\models\shop;

use Yii;
use common\models\main\Contents;
use common\models\gallery\GalleryImages;

/**
 * This is the model class for table "shop_property_values".
 *
 * @property integer $id
 * @property integer $shop_properties_id
 * @property string $name
 * @property string $anchor
 * @property string $url
 * @property integer $contents_id
 * @property integer $gallery_images_id
 *
 * @property GalleryImage $galleryImage
 */
class ShopPropertyValues extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_property_values';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_properties_id', 'contents_id', 'gallery_images_id'], 'integer'],
            [['name', 'anchor', 'url'], 'string', 'max' => 255],
            [['shop_properties_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopProperties::className(), 'targetAttribute' => ['shop_properties_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_properties_id' => 'Shop Properties ID',
            'name' => 'Name',
            'anchor' => 'Anchor',
            'url' => 'Url',
            'contents_id' => 'Contents ID',
            'gallery_images_id' => 'Gallery Images ID'
        ];
    }

    public function getContent()
    {
        return $this->hasOne(Contents::className(), ['id' => 'contents_id']);
    }

    public function getGalleryImage()
    {
        return $this->hasOne(GalleryImages::className(), ['id' => 'gallery_images_id']);
    }
}
