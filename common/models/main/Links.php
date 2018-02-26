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
 * @property integer $avg_rating
 * @property string $h1
 * @property integer $manually_state
 *
 * @property Contents[] $contents
 * @property Layouts $layouts
 * @property Views $views
 * @property Categories $category
 * @property Links $parentLink
 * @property Links[] $links
 * @property Links[] $activeLinks
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
            [['categories_id', 'layouts_id', 'views_id', 'parent', 'child_exist', 'level', 'seq', 'gallery_images_id', 'start', 'created_at', 'updated_at', 'state', 'content_nums', 'manually_state'], 'integer'],
            [['priority', 'avg_rating'], 'number'],
            [['url', 'name', 'anchor', 'css_class', 'icon', 'h1'], 'string', 'max' => 255],
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
            'title' => 'Заголовок Title',
            'keywords' => 'Значение тега Keywords',
            'description' => 'Значение тега Description',
            'gallery_images_id' => 'Изображение',
            'start' => 'Главная',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'priority' => 'Приоритет (применимо к sitemap.xml)',
            'state' => 'Активная (опубликованная) страница',
            'content_nums' => 'Content Nums',
            'css_class' => 'Класы стилей',
            'icon' => 'Иконка',
            'avg_rating' => 'Средний рейтинг',
            'h1' => 'Заголовок h1',
            'manually_state' => 'Смена состояния вручную'
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
        return $this->hasMany(Links::className(), ['parent' => 'id'])->orderBy(['seq' => SORT_ASC]);
    }

    public function getActiveLinks()
    {
        return $this->hasMany(Links::className(), ['parent' => 'id'])->where(['state' => true])->orderBy(['seq' => SORT_ASC]);
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
            if (!$this->seq) {
                $this->child_exist = 0;
                $this->level = 1;
                $this->seq = $this->findLastSequence($this->categories_id, $this->parent) + 1;
            }

            if ($this->parent) {
                $parentLink = self::findOne($this->parent);
                $this->level = $parentLink->level + 1;
                if ($parentLink->child_exist == 0) {
                    $parentLink->child_exist = 1;
                    $parentLink->save();
                }
            }

            if (self::findOne(['url' => $this->url])) {
                Yii::$app->getSession()->setFlash('danger', 'Адрес страницы (URL) уже существует на сайте. Вам следует указать другое наименование латиницай.');
                return false;
            }

//            $ancestor = new Ancestors();
//            $ancestor->updateAncestor($this);

            return true;
        } else {
            $link = self::findOne([$this->id]);
            if ($link && $this->url != $link->url) {
                $redirect = new Redirects();
                $redirect->links_id = $link->id;
                $redirect->url = $link->url;
                $redirect->code = 301;
                $redirect->save();
            }

//            $ancestor = new Ancestors();
//            $ancestor->updateAncestor($this);

            return true;
        }

        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub

        $ancestor = new Ancestors();
        $ancestor->updateAncestor($this);

        $this->updateState($this);
    }

    public function afterDelete()
    {
        self::reSort($this->categories_id, $this->parent);
        if ($this->parent) {
            if (self::find()->where(['parent' => $this->parent])->count() == 0) {
                $parentLink = self::findOne($this->parent);
                $parentLink->child_exist = 0;
                $parentLink->save();
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

    /**
     * @param Links $link
     */
    public function updateState($link)
    {
        $ancestorLinkGroups = Ancestors::find()->innerJoinWith(['ancestorLink'])->where(['ancestors.links_id' => $link->id])->all();

        if ($ancestorLinkGroups && $link->state) {
            $state = true;
            /** @var Ancestors $ancestorLinkGroup */
            foreach ($ancestorLinkGroups as $ancestorLinkGroup) {
                if ($ancestorLinkGroup->ancestorLink->state == false) $state = false;
            }

            if ($link->state != $state) {
                Links::updateAll(['state' => $state], ['id' => $link->id]);
                echo "update state ".$link->id."\n";
            }
        }
    }
}
