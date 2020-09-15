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
    public $parent = null;
    public $linksId = null;
    private $parentsIds;

    public function init()
    {
        if (empty($this->categories_id)) {
            throw new InvalidConfigException("The 'categories_id' property has not been set.");
        }

        if ($this->linksId) {
            $this->parentsIds = (new \common\models\main\Links())->getParentsIds($this->linksId);
        }
    }

    public function getLinksDef($parent)
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
         * @var  $i
         * @var \common\models\main\Links $link
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
                                ['label' => 'Параметры', 'url' => ['/map/links', 'categories_id' => Yii::$app->request->get('categories_id'), 'action' => 'ch', 'id' => $link->id]],
                                ['label' => 'Ретактор контента', 'url' => ['/map/content', 'links_id' => $link->id]],
                                ['label' => 'Добавить дочернюю ссылку', 'url' => ['/map/links', 'categories_id' => Yii::$app->request->get('categories_id'), 'parent' => $link->id, 'action' => 'add']],
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

    public function getLinks($parent)
    {
        $links = \common\models\main\Links::find()
            ->innerJoinWith('category')
            ->where(['categories_id' => $this->categories_id])
            ->andWhere(['parent' => $parent])
            ->andWhere(['categories.visible' => 1])
            ->orderBy(['seq' => SORT_ASC])
            ->all();

        if (!$links) return '<em class="text-muted">Ссылки не добавлены или скрыты.</em>';

        $html = Html::beginTag('ul', [
            'class' => 'list-unstyled links-list'
        ]);

        /**
         * @var  $i
         * @var \common\models\main\Links $link
         */
        foreach ($links as $i => $link) {
            $childLinkAction = '';
            $childrenBlock = '';
            if ($link->child_exist == 1) {
                if ($this->parentsIds && in_array($link->id, $this->parentsIds)) {
                    $iconClass = 'fa fa-minus-square-o';
                    $status = 'hide';
                    $childrenBlock = $this->getLinks($link->id);

                    $childrenClass = ' show';
                } else {
                    $iconClass = 'fa fa-plus-square-o';
                    $status = 'show';
                    $childrenBlock = '';
                    $childrenClass = '';
                }
                $childLinkAction = Html::a('<i class="'.$iconClass.'" aria-hidden="true"></i>', false, [
                    'class' => 'text-muted get-children',
                    'data-status' => $status,
                    'data-categories-id' => $link->categories_id,
                    'data-parent' => $link->id,
                    'data-level' => $link->level,
                    'onclick' => 'getChildren($(this))'
                ]);

                $childrenBlock = Html::tag('div', $childrenBlock, [
                    'class' => 'children-block' . $childrenClass,
                    'id' => 'children-block-link-'.$link->id
                ]);
            }

            $anchor = $link->state   ? $link->anchor : Html::tag('s', $link->anchor);

            $html .= Html::tag('li',
                Html::tag('div',
                    Html::beginTag('div', ['class' => 'row']) .
                    Html::tag('div',
                        $childLinkAction .
                        Html::a($anchor, ['/map/content', 'links_id' => $link->id], ['class' => ($link->id == $this->linksId ? 'active' : '')]),
                        ['class' => 'col-sm-9 col-md-10']) .
                    Html::tag('div',
                        ButtonDropdown::widget([
                            'label' => '<i class="glyphicon glyphicon-option-vertical"></i>',
                            'dropdown' => [
                                'items' => [
                                    ['label' => 'Параметры', 'url' => ['/map/links', 'categories_id' => $this->categories_id, 'action' => 'ch', 'id' => $link->id]],
                                    ['label' => 'Ретактор контента', 'url' => ['/map/content', 'links_id' => $link->id]],
                                    ['label' => 'Добавить дочернюю ссылку', 'url' => ['/map/links', 'categories_id' => $this->categories_id, 'parent' => $link->id, 'action' => 'add']],
                                ],
                            ],
                            'encodeLabel' => false,
                            'options' => [
                                'class' => 'btn btn-link btn-xs',
                            ]
                        ]), ['class' => 'col-sm-3 col-md-2 action text-right']) .
                    Html::endTag('div'), [
                        'class' => 'inner' . ($link->id == $this->linksId ? ' active' : '')
                    ]) .
                $childrenBlock
            );

        }
        $html .= Html::endTag('ul');

        return $html;
    }

    public function run()
    {
        echo $this->getLinks($this->parent);

        MapAsset::register($this->view);
    }
}
?>
