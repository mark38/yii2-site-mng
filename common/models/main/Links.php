<?php

namespace common\models\main;

use Yii;
use common\models\helpers\Translit;
use common\models\gallery\GalleryGroups;
use common\models\gallery\GalleryImages;

/**
 * This is the model class for table "links".
 *
 * @property string $id
 * @property integer $categories_id
 * @property integer $layouts_id
 * @property integer $views_id
 * @property string $parent
 * @property string $url
 * @property string $name
 * @property string $anchor
 * @property integer $child_exist
 * @property integer $level
 * @property string $seq
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property integer $gallery_images_id
 * @property integer $start
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $priority
 * @property integer $state
 * @property string $content_nums
 * @property string $css_class
 * @property string $icon
 *
 * @property Contents[] $contents
 * @property Layouts $layouts
 * @property Views $views
 * @property Categories $categories
 * @property Links $parentLink
 * @property Links[] $links
 * @property Redirects[] $redirects
 * @property GalleryImages $galleryImage
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
            [['anchor'], 'required'],
            [['categories_id', 'layouts_id', 'views_id', 'parent', 'child_exist', 'level', 'seq', 'gallery_images_id', 'start', 'created_at', 'updated_at', 'state', 'content_nums'], 'integer'],
            [['priority'], 'number'],
            [['url', 'name', 'anchor', 'css_class', 'icon'], 'string', 'max' => 255],
            [['title', 'keywords', 'description'], 'string', 'max' => 1024],
            [['url'], 'unique']
        ];
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
            'name' => 'Наименование латиницей',
            'anchor' => 'Наименование ссылки (анкор)',
            'child_exist' => 'Child Exist',
            'level' => 'Level',
            'seq' => 'Seq',
            'title' => 'Заголовок',
            'keywords' => 'Заполнение meta-тека "Keywords"',
            'description' => 'Заполнение meta-тека "Description"',
            'gallery_images_id' => 'Изображение ссылки',
            'start' => 'Главная',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'priority' => 'Приоритет (применимо к sitemap.xml)',
            'state' => 'Активная (опубликованная) страница',
            'content_nums' => 'Content Nums',
            'css_class' => 'Класы стилей',
            'icon' => 'Иконка',
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
     * @return \yii\db\`ctiveQuery
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
    public function getGalleryImage()
    {
        return $this->hasOne(GalleryImages::className(), ['id' => 'gallery_images_id']);
    }

    public function getGalleryGroup()
    {
        return $this->hasOne(GalleryGroups::className(), ['id' => 'gallery_groups_id'])->via('galleryImage');
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
        if ($this->start == 1) {
            $this->url = '/';
        }

        if (!$this->name) {
            $translit = new Translit();
            $parent = isset($this->parent) ? $this->parent : null;
            $this->name = $insert ? $translit->slugify($this->anchor, $this->tableName(), 'name', '-', $this->id, 'parent', $parent) : $translit->slugify($this->anchor, $this->tableName(), 'name', '-', null, 'parent', $parent);
        }

        if (!$this->url) {
            $this->url = $this->parent ? preg_replace('/\/$/', '', self::findOne($this->parent)->url).'/'.$this->name : '/'.$this->name;
        }

        if ($insert) {
            $this->child_exist = 0;
            $this->level = 1;
            $this->seq = $this->findLastSequence($this->categories_id, $this->parent) + 1;

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
            $link = self::findOne([$this->id]);
            if ($link && $this->url != $link->url) {
                $redirect = new Redirects();
                $redirect->links_id = $link->id;
                $redirect->url = $link->url;
                $redirect->save();
            }
            return true;
        }

        return true;
    }

    /*public function beforeSave($insert)
    {
        if (!$this->name) {
            $this->name = $this->anchor2translit(preg_replace('/\s\/.+$/', '', $this->anchor));
        }

        if ($this->start == 1) {
            $this->url = '/';
        } else {
            $this->url = $this->parent ? preg_replace('/\/$/', '', self::findOne($this->parent)->url).'/'.$this->name : '/'.$this->name;
        }

        if ($insert) {
            $this->child_exist = 0;
            $this->level = 1;
            $this->seq = $this->findLastSequence($this->categories_id, $this->parent) + 1;

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
            echo 'UPDATE'.$this->url.'<br>';
            $link = self::findOne([$this->id]);
            if ($link && $this->url != $link->url) {
                $redirect = new Redirects();
                $redirect->links_id = $link->id;
                $redirect->url = $link->url;
                $redirect->save();
            }
            return true;
        }
    }*/

    public function afterDelete()
    {
        self::reSort($this->categories_id, $this->parent);
        if ($this->parent) {
            if (self::find()->where(['parent' => $this->parent])->count() == 0) {
                $parent_link = self::findOne($this->parent);
                $parent_link->child_exist = 0;
                $parent_link->save();
            }
        }
    }

    public function getPrefixUrl($pattern, $linkLevel, $parent=null)
    {
        $patternSplit = preg_split('/\//', preg_replace('/\/$/', '', $pattern));
        $prefixUrl = '';
        if ($patternSplit) {
            foreach ($patternSplit as $item) {
                if (preg_match('/{(level)-(\d+)}/', $item, $match)) {
                    if (isset($match[2]) && $linkLevel > $match[2]) {
                        $level = $linkLevel - 1;
                        $parentLink = self::findOne($parent);
                        while ($level > $match[2]) {
                            $level = $level - 1;
                            $parentLink = Links::findOne($parentLink->parent);
                        }
                        $prefixUrl .= $parentLink->name.'/';
                    }
                } else {
                    $prefixUrl .= $item.'/';
                }
            }
        }

        return preg_replace('/\/$/', '', $prefixUrl);
    }

    public function anchor2translit($anchor)
    {
        // ГОСТ 7.79B
        $transliteration = array(
            'А' => 'A', 'а' => 'a',
            'Б' => 'B', 'б' => 'b',
            'В' => 'V', 'в' => 'v',
            'Г' => 'G', 'г' => 'g',
            'Д' => 'D', 'д' => 'd',
            'Е' => 'E', 'е' => 'e',
            'Ё' => 'Yo', 'ё' => 'yo',
            'Ж' => 'Zh', 'ж' => 'zh',
            'З' => 'Z', 'з' => 'z',
            'И' => 'I', 'и' => 'i',
            'Й' => 'J', 'й' => 'j',
            'К' => 'K', 'к' => 'k',
            'Л' => 'L', 'л' => 'l',
            'М' => 'M', 'м' => 'm',
            'Н' => "N", 'н' => 'n',
            'О' => 'O', 'о' => 'o',
            'П' => 'P', 'п' => 'p',
            'Р' => 'R', 'р' => 'r',
            'С' => 'S', 'с' => 's',
            'Т' => 'T', 'т' => 't',
            'У' => 'U', 'у' => 'u',
            'Ф' => 'F', 'ф' => 'f',
            'Х' => 'H', 'х' => 'h',
            'Ц' => 'Cz', 'ц' => 'cz',
            'Ч' => 'Ch', 'ч' => 'ch',
            'Ш' => 'Sh', 'ш' => 'sh',
            'Щ' => 'Shh', 'щ' => 'shh',
            'Ъ' => 'ʺ', 'ъ' => 'ʺ',
            'Ы' => 'Y`', 'ы' => 'y`',
            'Ь' => '', 'ь' => '',
            'Э' => 'E`', 'э' => 'e`',
            'Ю' => 'Yu', 'ю' => 'yu',
            'Я' => 'Ya', 'я' => 'ya',
            '№' => '#', 'Ӏ' => '‡',
            '’' => '`', 'ˮ' => '¨',
            ' ' => '-'
        );

        $anchor = strtr($anchor, $transliteration);
        $anchor = mb_strtolower($anchor, 'UTF-8');
        $anchor = preg_replace('/[^0-9a-z\-]/', '', $anchor);
        $anchor = preg_replace('|([-]+)|s', '-', $anchor);
        $anchor = trim($anchor, '-');

        return $anchor;
    }

    public static function findByUrl($name, $parent=null)
    {
        return static::findOne(['name' => $name, 'parent' => $parent]);
    }

    public static function findByUrlForLink($name, $links_id, $parent=null)
    {
        return static::find()->where(['name' => $name])->andWhere(['<>', 'id', $links_id])->andWhere(['parent' => $parent])->all();
    }

    public static function findLastSequence($categoreis_id, $parent=null)
    {
        $q = static::find()->where(['categories_id' => $categoreis_id, 'parent' => $parent])->orderBy(['seq' => SORT_DESC])->one();
        return ($q ? $q->seq : 0);
    }

    public function reSort($categories_id, $parent=null)
    {
        $links = self::find()->where(['categories_id' => $categories_id, 'parent' => $parent])->orderBy(['seq' => SORT_ASC])->all();
        foreach ($links as $index => $link) {
            $link->seq = $index+1;
            $link->update();
        }
    }

    public function getParentsIds($childId)
    {
        $parentsIds = array();
        do {
            $link = self::findOne(['id' => $childId]);
            if ($link && $link->parent) {
                array_push($parentsIds, $link->parent);
                $childId = $link->parent;
            }
        } while($link && $link->parent != null);

        if ($parentsIds) {
            return array_reverse($parentsIds);
        } else {
            return false;
        }
    }
}
