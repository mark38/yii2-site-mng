<?php
/** @var $this \yii\web\View */

$items[] = ['label' => 'Новостной блок', 'options' => ['class' => 'header']];
$items[] = [
    'label' => 'Все нововсти',
    'icon' => 'fa fa-newspaper-o',
    'url' => ['/news/index'],
    'active' => $this->context->module->id == 'news' ? true : false,
];
$items[] = ['label' => 'Типы новостей', 'icon' => 'fa fa-circle-thin', 'url' => '#'];

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
