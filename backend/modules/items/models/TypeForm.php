<?php
namespace app\modules\items\models;

use Yii;
use common\models\items\ItemTypes;
use common\models\gallery\GalleryTypes;
use common\models\gallery\GalleryGroups;

class TypeForm extends ItemTypes
{
    public $galleryTypes;
    public $galleryGroupName;

    public function rules()
    {
        return array_merge(parent::rules(),[
            [['galleryGroupName'], 'string'],
        ]);
    }

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub

        $this->galleryTypes = GalleryTypes::find()->orderBy(['comment' => SORT_ASC])->all();
//        $this->links_id = null;
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'galleryGroupName' => 'Фото-галерея',
        ]);
    }

    public function afterFind()
    {
        parent::afterFind(); // TODO: Change the autogenerated stub
        $this->galleryGroupName = $this->galleryGroup->name;
    }

    public function beforeSave($insert)
    {
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub

        if ($this->gallery_groups_id) {
            $galleryGroup = GalleryGroups::findOne($this->gallery_groups_id);
            $galleryGroupSave = false;

            if ($galleryGroup->name != $this->galleryGroupName) {
                $galleryGroup->name = $this->galleryGroupName;
                $galleryGroupSave = true;
            }

            if ($galleryGroup->gallery_types_id != $this->gallery_types_id) {
                $galleryGroup->gallery_types_id = $this->gallery_types_id;
                $galleryGroupSave = true;
            }

            if ($galleryGroupSave) $galleryGroup->save();
        } else {
            $galleryGroup = new GalleryGroups();
            $galleryGroup->name = $this->galleryGroupName ? $this->galleryGroupName : $this->name;
            $galleryGroup->gallery_types_id = $this->gallery_types_id;
            $galleryGroup->save();

            $this->gallery_groups_id = $galleryGroup->id;
            $this->update();
        }
    }
}