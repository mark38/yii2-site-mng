<?php
use yii\bootstrap\Html;
use yii\bootstrap\Dropdown;
use kartik\grid\GridView;

/** @var $this \yii\web\View */
/** @var $dataProvider \yii\data\ActiveDataProvider */

$this->title = 'Шаблоны писем';
$this->params['breadcrumbs'][] = $this->title;

$cols[] = [
    'attribute' => 'Наименование',
    'value' => function($layout, $index) {
        return $layout->name;
    }
];

$cols[] = [
    'attribute' => 'Путь к файлу',
    'value' => function($layout, $index) {
        return $layout->layout_path;
    }
];

$cols[] = [
    'label' => '',
    'format' => 'raw',
    'contentOptions' =>['class' => 'menu-col'],
    'options' => ['style' => 'width:50px'],
    'value' => function ($layout) {
        $items[] = ['label' => 'Редактировать', 'url' =>['/broadcast/layout-mng', 'id' => $layout->id]];

        return Html::tag('div',
            '<span class="btn btn-flat menu-button dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-option-vertical"></i></span>' .
            Dropdown::widget([
                'items' => $items,
                'options' => [
                    'class' => 'control-ul',
                ]
            ]),
            ['class' => 'dropdown']);
    },
];

?>


<div class="box box-default">
    <div class="box-header with-border"><?=Html::a('<i class="fa fa-plus"></i> Новый шаблон', ['/broadcast/layout-mng'], ['class' => 'btn btn-default btn-flat'])?></div>
    <div class="box-body">
        <?=GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => $cols,

            'bordered' => true,
            'striped' => false,
            'condensed' => true,
            'responsive' => false,
            'hover' => true,
        ])?>
    </div>
</div>
