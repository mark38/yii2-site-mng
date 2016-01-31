<?php

namespace common\models\main;

use common\models\gl\GlGroups;
use Yii;
use common\models\gl\GlImgs;

/**
 * This is the model class for table "links".
 *
 * @property string $id
 * @property integer $categories_id
 * @property integer $layouts_id
 * @property integer $views_id
 * @property string $parent
 * @property string $url
 * @property string $link_name
 * @property string $anchor
 * @property integer $child_exist
 * @property integer $level
 * @property string $seq
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property string $gl_imgs_id
 * @property integer $start
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $priority
 * @property integer $state
 * @property string $content_nums
 *
 * @property Contents[] $contents
 * @property Layouts $layouts
 * @property Views $views
 * @property Categories $categories
 * @property Links $parent0
 * @property Links[] $links
 * @property ModGlImgs $glImgs
 * @property ModGlGroups[] $modGlGroups
 * @property ModShGoods[] $modShGoods
 * @property ModShGroups[] $modShGroups
 * @property Redirects[] $redirects
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
            [['anchor', 'title'], 'required'],
            [['categories_id', 'layouts_id', 'views_id', 'parent', 'child_exist', 'level', 'seq', 'gl_imgs_id', 'start', 'created_at', 'updated_at', 'state', 'content_nums'], 'integer'],
            [['priority'], 'number'],
            [['url', 'link_name', 'anchor'], 'string', 'max' => 255],
            [['title', 'keywords', 'description'], 'string', 'max' => 1024],
            [['url'], 'unique']
        ];
    }

    public function init() {
        $this->state = 1;
        $this->priority = '0.5';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'categories_id' => 'Категория',
            'layouts_id' => 'Шаблон страницы',
            'views_id' => 'Вид страницы',
            'parent' => 'Parent',
            'url' => 'Адрес страницы (URL)',
            'link_name' => 'Наименование латиницай',
            'anchor' => 'Ннаименование ссылки (анкор)',
            'child_exist' => 'Child Exist',
            'level' => 'Level',
            'seq' => 'Seq',
            'title' => 'Заголовок',
            'keywords' => 'Заполнение meta-тека "Keywords"',
            'description' => 'Заполнение meta-тека "Description"',
            'gl_imgs_id' => 'Gl Imgs ID',
            'start' => 'Start',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'priority' => 'Приоритет (применимо к sitemap.xml)',
            'state' => 'Активная (опубликованная) страница',
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
    public function getLayout()
    {
        return $this->hasOne(Layouts::className(), ['id' => 'layouts_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getView()
    {
        return $this->hasOne(Views::className(), ['id' => 'views_id']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRedirects()
    {
        return $this->hasMany(Redirects::className(), ['links_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->child_exist = 0;
            $this->level = 1;
            $this->seq = $this->findLastSequence($this->categories_id) + 1;

            if (!$this->link_name) {
                $this->link_name = $this->anchor2translit(preg_replace('/\s\/.+$/', '', $this->anchor));
            }
            if ($this->url) {
                $link = self::findOne([$this->id]);
                if ($link && $this->url != $link->url) {
                    $redirect = new Redirects();
                    $redirect->links_id = $link->id;
                    $redirect->url = $link->url;
                    $redirect->save();
                }
            } else {
                $this->url = !$this->parent ? self::findOne($this->parent)->url.'/'.$this->link_name : '/'.$this->link_name;
            }

            if ($this->parent) {
                $parent_link = self::findOne($this->parent);
                $this->level = $parent_link->level + 1;
                if ($parent_link->child_exist == 0) {
                    $parent_link->child_exist = 1;
                    $parent_link->save();
                }
            }

            if (self::findOne(['url' => $this->url])) {
                Yii::$app->getSession()->setFlash('danger', 'Адрес страницы (URL) уже существует на сайте. Вам следует указать другое наименование латиницай.');
                return false;
            }

            return true;
        } else {
            return true;
        }
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
            '%' => '',      '`' => '',      '^' => '',
            '+' => '',      '(' => '',      ')' => '',
            '.' => '',      '!' => '',      '’' => '',
        );

        $anchor = strtolower($anchor);
        $anchor = preg_replace('/\s+/', ' ', trim($anchor));
        $anchor = preg_replace('/\-+/', '-', $anchor);

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

    public function reSort($parent_links_id)
    {
        $links = self::find()->where(['parent' => $parent_links_id])->orderBy(['seq' => SORT_ASC])->all();
        foreach ($links as $index => $link) {
            $link->seq = $index+1;
            $link->update();
        }
    }
}
