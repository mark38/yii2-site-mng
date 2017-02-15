<?php
/** @var $this \yii\web\View */

$items[] = ['label' => 'Аукцион MB', 'options' => ['class' => 'header']];
$items[] = [
    'label' => 'Лоты',
    'icon' => 'fa fa-list',
    'url' => ['/auctionmb/lots'],
    'active' => Yii::$app->controller->action->id == 'lots' ? true : false,
];
$items[] = [
    'label' => 'Типы лотов',
    'icon' => 'fa fa-id-card-o',
    'url' => ['/auctionmb/types'],
    'active' => Yii::$app->controller->action->id == 'types' ? true : false,
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
