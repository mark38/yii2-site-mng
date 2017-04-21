<?php
use yii\bootstrap\Html;

/**
 * @var $this \yii\web\View
 */

$items[] = ['label' => 'Продукты', 'options' => ['class' => 'header']];
$items[] = [
    'label' => 'Группы и Товар',
    'icon' => 'sitemap',
    'url' => ['/shop/products/links'],
    'encode' => false,
];
$items[] = [
    'label' => 'Свойства',
    'icon' => 'filter',
    'url' => ['/shop/properties/list'],
];

$items[] = ['label' => 'Клиенты', 'options' => ['class' => 'header']];
$items[] = [
    'label' => 'Заказы',
    'icon' => 'circle-thin',
    'url' => ['/shop/clients/orders'],
    'encode' => false,
];

$items[] = ['label' => 'Импорт данных', 'options' => ['class' => 'header']];
$items[] = [
    'label' => 'Обмен данными с 1с',
    'icon' => 'circle-thin',
    'url' => ['/shop/hand-import'],
];

$items[] = ['label' => 'Поддержка', 'options' => ['class' => 'header']];
$items[] = [
    'label' => 'Важно',
    'icon' => 'circle-thin',
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
