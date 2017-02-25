<?php

namespace common\models\auctionmb;

use Yii;
use common\models\gallery\GalleryImages;

/**
 * This is the model class for table "auctionmb_payment_systems".
 *
 * @property integer $id
 * @property string $name
 * @property integer $gallery_images_id
 * @property integer $state
 *
 * @property GalleryImages $galleryImage
 * @property AuctionmbTakings[] $auctionmbTakings
 */
class AuctionmbPaymentSystems extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auctionmb_payment_systems';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gallery_images_id', 'state'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['gallery_images_id'], 'exist', 'skipOnError' => true, 'targetClass' => GalleryImages::className(), 'targetAttribute' => ['gallery_images_id' => 'id']],
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
            'gallery_images_id' => 'Gallery Images ID',
            'state' => 'State',
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
    public function getAuctionmbTakings()
    {
        return $this->hasMany(AuctionmbTakings::className(), ['auctionmb_payment_systems_id' => 'id']);
    }
}
