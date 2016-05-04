<?php

namespace common\models\shop;

use Yii;
use common\models\gallery\GalleryGroups;
use common\models\gallery\GalleryTypes;

/**
 * This is the model class for table "shop_good_images".
 *
 * @property integer $id
 * @property integer $shop_goods_id
 * @property integer $gallery_types_id
 * @property integer $gallery_groups_id
 *
 * @property ShopGood $shopGoods
 * @property GalleryType $galleryTypes
 * @property GalleryGroup $galleryGroups
 */
class ShopGoodGallery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_good_gallery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_goods_id', 'gallery_types_id', 'gallery_groups_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_goods_id' => 'Shop Goods ID',
            'gallery_types_id' => 'Gallery Types ID',
            'gallery_groups_id' => 'Gallery Groups ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopGood()
    {
        return $this->hasOne(ShopGoods::className(), ['id' => 'shop_goods_id']);
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
    public function getGalleryGroup()
    {
        return $this->hasOne(GalleryGroups::className(), ['id' => 'gallery_groups_id']);
    }
}
