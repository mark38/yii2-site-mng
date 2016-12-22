<?php
use yii\bootstrap\Html;
use yii\bootstrap\ButtonDropdown;


/** @var $properties \common\models\shop\ShopProperties */

echo Html::beginTag('table', ['class' => 'table table-condensed table-hover']);

echo Html::beginTag('thead');
echo Html::tag('tr',
    Html::tag('th', '#') .
    Html::tag('th', 'Наименование') .
    Html::tag('th', 'Наименование на сайте') .
    Html::tag('th', 'Имя латиницей')
);
echo Html::endTag('thead');

/** @var \common\models\shop\ShopProperties $property */
print_r($properties);
/*foreach ($properties as $num => $property) {
    echo Html::tag('tr',
        Html::tag('td', Html::tag(($property->state == 1 ? 'span' : 'del'), ($num+1))),
        Html::tag('td', Html::tag(($property->state == 1 ? 'span' : 'del'), ($property->name))) .
        Html::tag('td', Html::tag(($property->state == 1 ? 'span' : 'del'), ($property->anchor))) .
        Html::tag('td', Html::tag(($property->state == 1 ? 'span' : 'del'), ($property->url))) .
        Html::tag('td', ButtonDropdown::widget([
            'label' => '<i class="glyphicon glyphicon-option-vertical"></i>',
            'dropdown' => [
                'items' => [
                    ['label' => 'Параметры', 'url' => ['', 'properties_id' => $property->id, 'action' => 'property_ch']],
                    ['label' => 'Возможные значения', 'url' => ['', 'properties_id' => $property->id, 'action' => 'get_values']],
                ],
            ],
            'encodeLabel' => false,
            'options' => [
                'class' => 'btn btn-link btn-xs clear-caret',
            ]
        ])),
        ['class' => (isset(Yii::$app->request->get('properties_id')) && Yii::$app->request->get('properties_id') == $property->id ? 'active' : '')]
    );
}*/

echo Html::endTag('table');