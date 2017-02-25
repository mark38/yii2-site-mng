<?php
/** @var $this \yii\web\View */

$items[] = ['label' => 'Аукцион MB', 'options' => ['class' => 'header']];
$items[] = [
    'label' => 'Лоты',
    'icon' => 'fa fa-list',
    'url' => ['/auctionmb/index'],
    'active' => Yii::$app->controller->action->id == 'index' ? true : false,
];
$items[] = [
    'label' => 'Аукционы',
    'icon' => 'fa fa-id-card-o',
    'url' => ['/auctionmb/lots'],
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
