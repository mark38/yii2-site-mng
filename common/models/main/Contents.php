<?php

namespace common\models\main;

use Yii;

/**
 * This is the model class for table "contents".
 *
 * @property string $id
 * @property string $links_id
 * @property string $parent
 * @property string $css_class
 * @property string $text
 * @property integer $seq
 *
 * @property Links $links
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
            [['text'], 'string'],
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
            'css_class' => 'Классы стилей',
            'text' => 'Text',
            'seq' => 'Порядковый номер',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLink()
    {
        return $this->hasOne(Links::className(), ['id' => 'links_id']);
    }

    public static function findLastSequence($links_id, $parent=null)
    {
        $q = static::find()->where(['links_id' => $links_id, 'parent' => $parent])->orderBy(['seq' => SORT_DESC])->one();
        return ($q ? $q->seq : 0);
    }

    public function reSort($links_id, $parent=null)
    {
        $contents = self::find()->where(['links_id' => $links_id, 'parent' => $parent])->orderBy(['seq' => SORT_ASC])->all();
        foreach ($contents as $index => $content) {
            $content->seq = $index+1;
            $content->update();
        }
    }
}
