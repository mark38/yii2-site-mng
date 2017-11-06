<?php

namespace common\models\main;

use Yii;

/**
 * This is the model class for table "ancestors".
 *
 * @property integer $id
 * @property integer $links_id
 * @property integer $ancestor_links_id
 *
 * @property Links $ancestorLink
 * @property Links $link
 * @property Categories $category
 * @property Categories $categoryAncestor
 */
class Ancestors extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ancestors';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['links_id', 'ancestor_links_id'], 'integer'],
            [['ancestor_links_id'], 'exist', 'skipOnError' => true, 'targetClass' => Links::className(), 'targetAttribute' => ['ancestor_links_id' => 'id']],
            [['links_id'], 'exist', 'skipOnError' => true, 'targetClass' => Links::className(), 'targetAttribute' => ['links_id' => 'id']],
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
            'ancestor_links_id' => 'Ancestor Links ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAncestorLink()
    {
        return $this->hasOne(Links::className(), ['id' => 'ancestor_links_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLink()
    {
        return $this->hasOne(Links::className(), ['id' => 'links_id']);
    }

    public function getCategory()
    {
        return $this->hasOne(Categories::className(), ['id' => 'categories_id'])->via('link');
    }

    public function getCategoryAncestor()
    {
        return $this->hasOne(Categories::className(), ['id' => 'categories_id'])->via('ancestorLink');
    }

    /**
     * @param Links $link
     */
    public function updateAncestor($link)
    {
        $parentLink = null;
        $parentLinks = array();
        $i = 0;
        if ($link->parentLink) {
            do {
                $parentLink = $parentLink === null ? $link->parentLink : $parentLink->parentLink;
                $parentLinks[$i]['id'] = $parentLink->id;
                $parentLinks[$i]['exist'] = false;
                $i += 1;
            } while ($parentLink->parent);
        }

        $ancestors = Ancestors::find()->where(['links_id' => $link->id])->all();
        $ancestorsExist = null;
        if ($ancestors) {
            /** @var Ancestors $ancestor */
            foreach ($ancestors as $ancestor) {
                $i = array_search($ancestor->ancestor_links_id, array_column($parentLinks, 'id'));
                if ($i !== false) {
                    $parentLinks[$i]['exist'] = true;
                } else {
                    $ancestorForDel = Ancestors::findOne($ancestor->id);
                    $ancestorForDel->delete();
                }
            }
        }

        if ($parentLinks) {
            /** @var Links $ancestorLinks */
            foreach ($parentLinks as $parentLink) {
                if (!$parentLink['exist']) {
                    $ancestor = new Ancestors();
                    $ancestor->links_id = $link->id;
                    $ancestor->ancestor_links_id = $parentLink['id'];
                    $ancestor->save();
                }
            }
        }
    }
}
