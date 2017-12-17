<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\main\Links;
use yii\helpers\ArrayHelper;

class NavLinks extends Model
{
    public function getLinks($activeLink)
    {
        return [
            'activeLink' => $activeLink,
            'breadcrumbs' => self::getBreadcrumbs($activeLink),
            'header' => self::getNav(1, null),
            'top' => self::getNav(2, null),
        ];
    }

    /** @var Links $link */
    private function getBreadcrumbs($link)
    {
        $breadcrumbs = array();
        $breadcrumbs[] = $link;
        if ($link->level > 1) {
            do {
                $link = Links::findOne($link->parent);
                $breadcrumbs[] = $link;
            } while($link->level > 1);
        }

        asort($breadcrumbs);

        return $breadcrumbs;
    }

    private function getNav($categoriesId, $parent=null)
    {
        $links = Links::find()
            ->where(['categories_id' => $categoriesId, 'parent' => $parent, 'state' => true, 'start' => false])
            ->orderBy(['seq' => SORT_ASC])
            ->all();

        return $links;
    }
}