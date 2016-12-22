<?php
/** @var $this \yii\web\View */

$items[] = ['label' => 'Параметры', 'options' => ['class' => 'header']];
$items[] = [
    'label' => 'Свойства товара',
    'icon' => 'fa fa-circle-thin',
    'url' => ['/shop/properties/list'],
];

$items[] = ['label' => 'Импорт данных', 'options' => ['class' => 'header']];
$items[] = [
    'label' => 'Обмен данными с 1с',
    'icon' => 'fa fa-circle-thin',
    'url' => ['/shop/hand-import'],
];

$items[] = ['label' => 'Поддержка', 'options' => ['class' => 'header']];
$items[] = [
    'label' => 'Важно',
    'icon' => 'fa fa-circle-thin',
    'url' => ['/shop/support/readme'],
    'active' => $this->context->module->id == 'readme' ? true : false,
];


?>
<aside class="main-sidebar">

    <section class="sidebar">
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => $items,
            ]
        ) ?>

    </section>

</aside>
