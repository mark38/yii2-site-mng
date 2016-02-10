<?php

namespace common\models\tor;

use common\models\gallery\GalleryGroups;
use common\models\geobase\GeobaseCity;
use common\models\geobase\GeobaseRegion;
use common\models\main\Links;
use common\models\User;
use Yii;

/**
 * This is the model class for table "tor_ads".
 *
 * @property integer $id
 * @property string $links_id
 * @property integer $user_id
 * @property string $region_id
 * @property string $city_id
 * @property integer $gallery_groups_id
 * @property double $price
 * @property double $reward
 * @property string $name
 * @property string $description
 * @property string $views
 * @property integer $created_at
 *
 * @property GalleryGroups $galleryGroups
 * @property GeobaseCity $city
 * @property GeobaseRegion $region
 * @property Links $links
 * @property User $user
 */
class TorAds extends \yii\db\ActiveRecord
{
    public $geobase_city;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tor_ads';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['links_id', 'user_id', 'region_id', 'city_id', 'gallery_groups_id', 'views', 'created_at'], 'integer'],
            [['price', 'reward'], 'number'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 1024]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'links_id' => 'Links ID',
            'user_id' => 'User ID',
            'region_id' => 'Region ID',
            'city_id' => 'City ID',
            'gallery_groups_id' => 'Gallery Groups ID',
            'price' => 'Price',
            'reward' => 'Reward',
            'name' => 'Name',
            'description' => 'Description',
            'views' => 'Views',
            'created_at' => 'Created At',
        ];
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
    public function getCity()
    {
        return $this->hasOne(GeobaseCity::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(GeobaseRegion::className(), ['id' => 'region_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinks()
    {
        return $this->hasOne(Links::className(), ['id' => 'links_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
