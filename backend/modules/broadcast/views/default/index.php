<?php
use yii\bootstrap\Html;
use yii\bootstrap\Dropdown;
use yii\grid\GridView;
use yii\bootstrap\Nav;

/**
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

$this->title = 'История отправок';
$this->params['breadcrumbs'][] = $this->title;


$cols[] = ['class' => 'yii\grid\SerialColumn'];

$cols[] = [
    'attribute' => 'title',
    'value' => 'title',
];

$cols[] = 'h1';

$cols[] = [
    'attribute' => 'broadcast_layouts_id',
    'value' => 'broadcastLayout.name',
];

$cols[] = [
    'attribute' => 'created_at',
    'class' => 'yii\grid\DataColumn',
    'value' => function ($data) {
        return $data->created_at ? date('d.m.Y', $data->created_at) : '-';
    },
];

$cols[] = [
    'attribute' => 'Всего отправлено',
    'value' => function($data){
        return $data->broadcastSendAmount;
    },
];

$cols[] = [
    'label' => '',
    'format' => 'raw',
    'contentOptions' =>['class' => 'menu-col'],
    'options' => ['style' => 'width:50px'],
    'value' => function ($data) {
        $items[] = ['label' => 'Редактировать', 'url' =>['/broadcast/manager', 'id' => $data->id]];
        $items[] = ['label' => 'Подготовить к отправке', 'url' =>['/broadcast/render-send', 'broadcast_id' => $data->id]];

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
    <div class="box-header with-border"><?=Html::a('<i class="glyphicon glyphicon-envelope"></i> Новое письмо', ['/broadcast/manager'], ['class' => 'btn btn-default btn-flat'])?></div>
    <div class="box-body">
        <?=GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => $cols
        ])?>
    </div>
</div>



