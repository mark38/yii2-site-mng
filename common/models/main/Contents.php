<?php

namespace common\models\main;

use Yii;

/**
 * This is the model class for table "contents".
 *
 * @property integer $id
 * @property integer $links_id
 * @property integer $parent
 * @property string $css_class
 * @property string $content
 * @property integer $seq
 *
 * @property Links $link
 */
class Contents extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contents';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['links_id', 'parent', 'seq'], 'integer'],
            [['content'], 'string'],
            [['css_class'], 'string', 'max' => 255]
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
            'parent' => 'Parent',
            'css_class' => 'Css Class',
            'content' => 'Content',
            'seq' => 'Seq',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLink()
    {
        return $this->hasOne(Links::className(), ['id' => 'links_id']);
    }
}
