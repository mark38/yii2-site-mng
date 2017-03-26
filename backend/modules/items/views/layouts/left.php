<?php
/** @var $this \yii\web\View */

$items[] = ['label' => 'Элементы контента', 'options' => ['class' => 'header']];
$items[] = [
    'label' => 'Все элементы',
    'icon' => 'fa fa-id-card-o',
    'url' => ['/items/index'],
    'active' => $this->context->module->id == 'items' ? true : false,
];
$items[] = ['label' => 'Типы элементов', 'icon' => 'fa fa-circle-thin', 'url' => '#'];

$items[] = ['label' => 'Поддержка', 'options' => ['class' => 'header']];
$items[] = [
    'label' => 'Важно',
    'icon' => 'fa fa-circle-thin',
    'url' => ['/items/support/readme'],
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
