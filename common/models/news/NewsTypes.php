<?php

namespace common\models\news;

use Yii;
use common\models\main\Links;
use common\models\main\Categories;
use common\models\gallery\GalleryTypes;
use common\models\main\Views;

/**
 * This is the model class for table "news_types".
 *
 * @property integer $id
 * @property integer $categories_id
 * @property string $links_id
 * @property string $name
 * @property integer $gallery_types_id
 * @property integer $gallery_groups_id
 * @property integer $views_id
 *
 * @property News[] $news
 * @property Categories $categories
 * @property Links $links
 * @property GalleryTypes $galleryTypes
 * @property Views $view
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
            [['categories_id', 'links_id', 'gallery_types_id', 'gallery_groups_id', 'views_id'], 'integer'],
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
            'categories_id' => 'Categories ID',
            'links_id' => 'Links ID',
            'name' => 'Name',
            'gallery_types_id' => 'Gallery Types ID',
            'views_id' => 'Views ID',
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
    public function getCategory()
    {
        return $this->hasOne(Categories::className(), ['id' => 'categories_id']);
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

    public function getView()
    {
        return $this->hasOne(Views::className(), ['id' => 'views_id']);
    }
}
