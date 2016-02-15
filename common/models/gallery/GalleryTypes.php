<?php

namespace common\models\gallery;

use Yii;

/**
 * This is the model class for table "{{%gallery_types}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $comment
 * @property string $destination
 * @property integer $small_width
 * @property integer $small_height
 * @property integer $large_width
 * @property integer $large_height
 * @property integer $quality
 * @property integer $visible
 *
 * @property GalleryGroups[] $galleryGroups
 */
class GalleryTypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gallery_types}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['small_width', 'small_height', 'large_width', 'large_height', 'quality', 'visible'], 'integer'],
            [['name', 'comment', 'destination'], 'string', 'max' => 255]
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
            'comment' => 'Comment',
            'destination' => 'Destination',
            'small_width' => 'Small Width',
            'small_height' => 'Small Height',
            'large_width' => 'Large Width',
            'large_height' => 'Large Height',
            'quality' => 'Quality',
            'visible' => 'Visible',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGalleryGroups()
    {
        return $this->hasMany(GalleryGroups::className(), ['gallery_types_id' => 'id']);
    }
}
