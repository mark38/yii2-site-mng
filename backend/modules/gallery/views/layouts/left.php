<?php
/** @var $this \yii\web\View */

$items[] = ['label' => 'Фотогалерея', 'options' => ['class' => 'header']];
$items[] = [
    'label' => 'Все фотогалереи',
    'icon' => 'fa fa-newspaper-o',
    'url' => ['/gallery/index'],
    'active' => $this->context->module->id == 'news' ? true : false,
];
$items[] = ['label' => 'Параметры фотографий', 'icon' => 'fa fa-circle-thin', 'url' => '#'];

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
