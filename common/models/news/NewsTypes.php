<?php

namespace common\models\news;

use Yii;
use common\models\main\Links;
use common\models\gallery\GalleryTypes;

/**
 * This is the model class for table "news_types".
 *
 * @property integer $id
 * @property string $links_id
 * @property string $name
 * @property integer $gallery_types_id
 *
 * @property News[] $news
 * @property Links $links
 * @property GalleryTypes $galleryTypes
 */
class NewsTypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['links_id', 'gallery_types_id'], 'integer'],
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
            'links_id' => 'Links ID',
            'name' => 'Name',
            'gallery_types_id' => 'Gallery Types ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasMany(News::className(), ['news_types_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLink()
    {
        return $this->hasOne(Links::className(), ['id' => 'links_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGalleryType()
    {
        return $this->hasOne(GalleryTypes::className(), ['id' => 'gallery_types_id']);
    }
}
