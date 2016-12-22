<?php
use yii\bootstrap\Html;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\Url;


/** @var $properties \common\models\shop\ShopPropertyValues */

$link_close = ['list'];

?>

<div class="box box-default">

    <div class="box-header with-border">
        <h3 class="box-title">
            Перечень значений свойств
            <?=ButtonDropdown::widget([
                'label' => '<i class="glyphicon glyphicon-option-vertical"></i>',
                'dropdown' => [
                    'items' => [
                        ['label' => 'Добавить значение', 'url' => Url::to(['properties_id' => Yii::$app->request->get('properties_id'), 'action' => 'value_add'])],
                    ],
                ],
                'encodeLabel' => false,
                'options' => [
                    'class' => 'btn btn-link btn-xs root-action clear-caret',
                ]
            ])?>
        </h3>
        <div class="box-tools pull-right">
            <?=Html::a('<i class="fa fa-times"></i>', $link_close, ['class' => 'btn btn-box-tool'])?>
        </div>
    </div>

    <div class="box-body">
        <?php

        echo Html::beginTag('table', ['class' => 'table table-condensed table-hover']);

        echo Html::beginTag('thead');
        echo Html::tag('tr',
            Html::tag('th', '#') .
            Html::tag('th', 'Наименование') .
            Html::tag('th', 'Наименование на сайте') .
            Html::tag('th', 'Имя латиницей')
        );
        echo Html::endTag('thead');

        /** @var \common\models\shop\ShopPropertyValues $value */
        foreach ($values as $num => $value) {
            echo Html::tag('tr',
                Html::tag('td', ($num+1)) .
                Html::tag('td', $value->name) .
                Html::tag('td', $value->anchor) .
                Html::tag('td', $value->url) .
                Html::tag('td', ButtonDropdown::widget([
                    'label' => '<i class="glyphicon glyphicon-option-vertical"></i>',
                    'dropdown' => [
                        'items' => [
                            ['label' => 'Параметры', 'url' => ['', 'properties_id' => $value->shop_properties_id, 'action' => 'value_ch', 'values_id' => $value->id]],
                        ],
                        'options' => [
                            'class' => 'dropdown-menu-right'
                        ]
                    ],
                    'encodeLabel' => false,
                    'options' => [
                        'class' => 'btn btn-link btn-xs clear-caret',
                    ]
                ]), [
                    'class' => 'text-right'
                ]),
                ['class' => (Yii::$app->request->get('properties_id') && Yii::$app->request->get('properties_id') == $property->id ? 'active' : '')]
            );
        }

        echo Html::endTag('table');

        ?>
    </div>

</div>
