<?php
namespace backend\widgets\map;

use Yii;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\Html;
use yii\base\Widget;
use yii\base\InvalidConfigException;

class Links extends Widget
{
    public $categories_id;

    public function init()
    {
        if (empty($this->categories_id)) {
            throw new InvalidConfigException("The 'categories_id' property has not been set.");
        }
    }

    public function getLinks($parent)
    {
        $links = \common\models\main\Links::find()
            ->innerJoinWith('category')
            ->where(['categories_id' => $this->categories_id])
            ->andWhere(['categories.visible' => 1])
            ->andWhere(['parent' => $parent])
            ->orderBy(['seq' => SORT_ASC])
            ->all();

        if (!$links) return '<em class="text-muted">Ссылки не добавлены</em>';

        $html = Html::beginTag('ul', [
            'class' => 'list-unstyled'
        ]);
        /**
         * @var $i
         * @var $link \common\models\main\Links
         */
        foreach ($links as $i => $link) {
            $html .= Html::tag('li',
                Html::beginTag('div', ['class' => 'row']) .
                Html::tag('div',
                    Html::a(
                        $link->anchor,
                        ['/map/content', 'links_id' => $link->id], [
                        'style' => ($link->state == 0 ? 'text-decoration: line-through; color: #aaa;' : '') .
                                   (Yii::$app->request->get('links_id') == $link->id || Yii::$app->request->get('parent_links_id') == $link->id ? 'font-weight: bold;' : '')
                    ]), ['class' => 'col-sm-9']) .
                Html::tag('div',
                    ButtonDropdown::widget([
                        'label' => '<i class="fa fa-cog"></i>',
                        'dropdown' => [
                            'items' => [
                                ['label' => 'Ретактор контента', 'url' => ['/map/content', 'links_id' => $link->id]],
                                ['label' => 'Ретактировать', 'url' => ['/map/links', 'categories_id' => Yii::$app->request->get('categories_id'), 'mng_link' => 'ch', 'links_id' => $link->id]],
                                ['label' => 'Добавить дочернюю ссылку', 'url' => ['/map/links', 'categories_id' => Yii::$app->request->get('categories_id'), 'parent_links_id' => $link->id, 'mng_link' => 'add']],
                            ],
                        ],
                        'encodeLabel' => false,
                        'options' => [
                            'class' => 'btn-default btn-xs',
                        ]
                    ]),
                    ['class' => 'col-sm-3 action text-right']) .
                Html::endTag('div')
            );

            if ($link->child_exist == '1') {
                $childs = \common\models\main\Links::find()
                    ->innerJoinWith('category')
                    ->where(['categories_id' => $this->categories_id])
                    ->andWhere(['categories.visible' => 1])
                    ->andWhere(['parent' => $link->id])
                    ->count();
                if ($childs > 0) $html .= $this->getLinks($link->id);
            }
        }
        $html .= Html::endTag('ul');

        return $html;
    }

    public function run()
    {
        echo $this->getLinks(null);

        MapAsset::register($this->view);
    }
}
?>
