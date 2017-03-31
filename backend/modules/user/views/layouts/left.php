<?php
use yii\bootstrap\Html;

/**
 * @var $this \yii\web\View
 */

$items[] = ['label' => 'Учётные записи', 'options' => ['class' => 'header']];
$items[] = [
    'label' => 'Профиль',
    'icon' => 'user',
    'url' => ['/user/profile'],
    'encode' => false,
    'active' => $this->context->module->id == 'profile' ? true : false,
];

$items[] = ['label' => 'Поддержка', 'options' => ['class' => 'header']];
$items[] = [
    'label' => 'Важно',
    'icon' => 'fa fa-circle-thin',
    'url' => ['/user/support/readme'],
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
