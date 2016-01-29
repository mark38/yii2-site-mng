<?php

namespace common\models\main;

use Yii;
use common\models\gl\GlImgs;

/**
 * This is the model class for table "links".
 *
 * @property integer $id
 * @property integer $categories_id
 * @property integer $layouts_id
 * @property integer $views_id
 * @property integer $parent
 * @property string $url
 * @property string $link_name
 * @property string $anchor
 * @property integer $child_exist
 * @property integer $level
 * @property integer $seq
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property integer $gl_imgs_id
 * @property integer $start
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $priority
 * @property integer $state
 * @property integer $content_nums
 *
 * @property Contents[] $contents
 * @property Categories $categories
 * @property Links $parentLink
 * @property Links[] $links
 * @property GlImgs $glImg
 */
class Links extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'links';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['categories_id'], 'required'],
            [['categories_id', 'layouts_id', 'views_id', 'parent', 'child_exist', 'level', 'seq', 'gl_imgs_id', 'start', 'created_at', 'updated_at', 'state', 'content_nums'], 'integer'],
            [['priority'], 'number'],
            [['url', 'title', 'keywords', 'description'], 'string', 'max' => 1024],
            [['link_name', 'anchor'], 'string', 'max' => 255]
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
            'layouts_id' => 'layouts ID',
            'views_id' => 'Views ID',
            'parent' => 'Parent',
            'url' => 'Url',
            'link_name' => 'Link Name',
            'anchor' => 'Anchor',
            'child_exist' => 'Child Exist',
            'level' => 'Level',
            'seq' => 'Seq',
            'title' => 'Title',
            'keywords' => 'Keywords',
            'description' => 'Description',
            'gl_imgs_id' => 'Gl Imgs ID',
            'start' => 'Start',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'priority' => 'Priority',
            'state' => 'State',
            'content_nums' => 'Content Nums',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContents()
    {
        return $this->hasMany(Contents::className(), ['links_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::className(), ['id' => 'categories_id']);
    }

    public function getLayout()
    {
        return $this->hasOne(Layouts::className(), ['id' => 'layouts_id']);
    }

    public function getView()
    {
        return $this->hasOne(Views::className(), ['id' => 'views_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentLink()
    {
        return $this->hasOne(Links::className(), ['id' => 'parent']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinks()
    {
        return $this->hasMany(Links::className(), ['parent' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGlImg()
    {
        return $this->hasOne(GlImgs::className(), ['id' => 'gl_imgs_id']);
    }

    public function anchor2translit($anchor)
    {
        $converter = array(
            'а' => 'a',		'б' => 'b',		'в' => 'v',
            'г' => 'g',		'д' => 'd',		'е' => 'e',
            'ё' => 'e',		'ж' => 'zh',	'з' => 'z',
            'и' => 'i',		'й' => 'y',		'к' => 'k',
            'л' => 'l',		'м' => 'm',		'н' => 'n',
            'о' => 'o',		'п' => 'p',		'р' => 'r',
            'с' => 's',		'т' => 't',		'у' => 'u',
            'ф' => 'f',		'х' => 'h',		'ц' => 'c',
            'ч' => 'ch',	'ш' => 'sh',	'щ' => 'sch',
            'ь' => '',		'ы' => 'y',		'ъ' => '',
            'э' => 'e',		'ю' => 'yu',	'я' => 'ya',

            'А' => 'a',		'Б' => 'b',		'В' => 'v',
            'Г' => 'g',		'Д' => 'd',		'Е' => 'e',
            'Ё' => 'e',		'Ж' => 'zh',	'З' => 'z',
            'И' => 'i',		'Й' => 'y',		'К' => 'k',
            'Л' => 'l',		'М' => 'm',		'Н' => 'n',
            'О' => 'o',		'П' => 'p',		'Р' => 'r',
            'С' => 's',		'Т' => 't',		'У' => 'u',
            'Ф' => 'f',		'Х' => 'h',		'Ц' => 'c',
            'Ч' => 'ch',	'Ш' => 'sh',	'Щ' => 'sch',
            'Ь' => '',		'Ы' => 'y',		'Ъ' => '',
            'Э' => 'e',		'Ю' => 'yu',	'Я' => 'ya',

            ' ' => '-',		'"' => '',		"'" => '',
            ':' => '',		"/" => '-',		"\\" => '',
            "," => '',      "*" => '',      "&" => 'i',
        );

        $anchor = strtolower($anchor);
        $anchor = preg_replace('/\s+/', ' ', trim($anchor));

        return strtr($anchor, $converter);
    }

    public static function findByUrl($link_name, $parent=null)
    {
        return static::findOne(['link_name' => $link_name, 'parent' => $parent]);
    }

    public static function findByUrlForLink($link_name, $links_id, $parent=null)
    {
        return static::find()->where(['link_name' => $link_name])->andWhere(['not in', 'id', $links_id])->andWhere(['parent' => $parent])->all();
    }

    public static function findLastSequence($categoreis_id, $parent=null)
    {
        $q = static::find()->where(['categories_id' => $categoreis_id, 'parent' => $parent])->orderBy(['seq' => SORT_DESC])->one();
        return ($q ? $q->seq : 0);
    }
}
