<?php

namespace common\models\shop;

use Yii;
use common\models\gallery\GalleryGroups;

/**
 * This is the model class for table "shop_good_images".
 *
 * @property integer $id
 * @property integer $shop_goods_id
 * @property integer $gallery_groups_id
 *
 * @property ShopGood $shopGoods
 * @property GalleryGroup $galleryGroups
 */
class ShopGoodImages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_good_images';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_goods_id', 'gallery_groups_id'], 'integer']
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
    public function getGalleryGroup()
    {
        return $this->hasOne(GalleryGroups::className(), ['id' => 'gallery_groups_id']);
    }
}
