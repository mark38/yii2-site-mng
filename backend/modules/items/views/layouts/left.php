<?php
/** @var $this \yii\web\View */

$items[] = ['label' => 'Элементы контента', 'options' => ['class' => 'header']];
$items[] = [
    'label' => 'Все элементы',
    'icon' => 'fa fa-id-card-o',
    'url' => ['/items/index'],
//    'active' => (stripos(Yii::$app->request->baseUrl.'/items', Yii::$app->request->url) !== null ? true : false),
];
$items[] = [
    'label' => 'Типы элементов',
    'icon' => 'circle-thin',
    'url' => Yii::$app->request->baseUrl.'/items/types/index',
//    'active' => (stripos(Yii::$app->request->baseUrl.'/items/types/', Yii::$app->request->url) !== null ? true : false),
];

$items[] = ['label' => 'Поддержка', 'options' => ['class' => 'header']];
$items[] = [
    'label' => 'Важно',
    'icon' => 'fa fa-circle-thin',
    'url' => ['/items/support/readme'],
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
