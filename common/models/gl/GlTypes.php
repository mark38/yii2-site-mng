<?php

namespace common\models\gl;

use Yii;

/**
 * This is the model class for table "mod_gl_types".
 *
 * @property integer $id
 * @property string $type
 * @property string $comment
 * @property string $dir_dst
 * @property integer $small_width
 * @property integer $small_height
 * @property integer $large_width
 * @property integer $large_height
 *
 * @property ModGlGroups[] $modGlGroups
 */
class GlTypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mod_gl_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['small_width', 'small_height', 'large_width', 'large_height'], 'integer'],
            [['type'], 'string', 'max' => 32],
            [['comment', 'dir_dst'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'comment' => 'Comment',
            'dir_dst' => 'Dir Dst',
            'small_width' => 'Small Width',
            'small_height' => 'Small Height',
            'large_width' => 'Large Width',
            'large_height' => 'Large Height',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModGlGroups()
    {
        return $this->hasMany(ModGlGroups::className(), ['types_id' => 'id']);
    }
}
