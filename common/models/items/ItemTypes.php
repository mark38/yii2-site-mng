<?php

namespace common\models\items;

use Yii;
use common\models\gallery\GalleryTypes;
use common\models\gallery\GalleryGroups;

/**
 * This is the model class for table "item_types".
 *
 * @property integer $id
 * @property string $name
 * @property integer $gallery_types_id
 * @property integer $gallery_groups_id
 *
 * @property GalleryGroups $galleryGroup
 * @property GalleryTypes $galleryType
 * @property Items[] $item
 */
class ItemTypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gallery_types_id', 'gallery_groups_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['gallery_groups_id'], 'exist', 'skipOnError' => true, 'targetClass' => GalleryGroups::className(), 'targetAttribute' => ['gallery_groups_id' => 'id']],
            [['gallery_types_id'], 'exist', 'skipOnError' => true, 'targetClass' => GalleryTypes::className(), 'targetAttribute' => ['gallery_types_id' => 'id']],
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
            'gallery_types_id' => 'Gallery Types ID',
            'gallery_groups_id' => 'Gallery Groups ID',
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
    public function getGalleryType()
    {
        return $this->hasOne(GalleryTypes::className(), ['id' => 'gallery_types_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(Items::className(), ['item_types_id' => 'id']);
    }
}
