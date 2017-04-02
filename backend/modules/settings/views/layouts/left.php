<?php
/** @var $this \yii\web\View */

$items[] = ['label' => 'Пользователи', 'options' => ['class' => 'header']];
$items[] = [
    'label' => 'Все пользователи',
    'icon' => 'fa fa-user',
    'url' => ['/settings/index'],
    'active' => Yii::$app->controller->action->id == 'index' ? true : false,
];
$items[] = [
    'label' => 'Роли пользователей',
    'icon' => 'fa fa-users',
    'url' => ['/settings/roles'],
    'active' => Yii::$app->controller->action->id == 'roles' ? true : false,
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
