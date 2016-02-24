<?php

namespace common\models\gl;

use Yii;
use common\models\main\Links;
use common\models\main\Contents;

/**
 * This is the model class for table "mod_gl_groups".
 *
 * @property integer $id
 * @property integer $types_id
 * @property integer $imgs_id
 * @property integer $links_id
 * @property integer $contents_id
 * @property string $groupname
 *
 * @property GlTypes $type
 * @property Links $links
 * @property Contents $contents
 * @property GlImgs $img
 * @property GlImgs[] $modGlImg
 */
class GlGroups extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mod_gl_groups';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['types_id', 'imgs_id', 'links_id', 'contents_id'], 'integer'],
            [['groupname'], 'string', 'max' => 1024]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'types_id' => 'Types ID',
            'imgs_id' => 'Imgs ID',
            'links_id' => 'Links ID',
            'contents_id' => 'Contents ID',
            'groupname' => 'Groupname',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(GlTypes::className(), ['id' => 'types_id']);
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
    public function getContents()
    {
        return $this->hasOne(Contents::className(), ['id' => 'contents_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImg()
    {
        return $this->hasOne(GlImgs::className(), ['id' => 'imgs_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGlImgs()
    {
        return $this->hasMany(GlImgs::className(), ['groups_id' => 'id'])->orderBy(['seq' => SORT_ASC]);
    }
}
