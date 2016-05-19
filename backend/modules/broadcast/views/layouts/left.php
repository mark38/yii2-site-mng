<?php
use app\modules\broadcast\BroadcastAssets;

/** @var $this \yii\web\View */

BroadcastAssets::register($this);


$items[] = ['label' => 'Email-рассылка', 'options' => ['class' => 'header']];
$items[] = [
    'label' => 'Рассылка',
    'icon' => 'fa fa-circle-thin',
    'url' => ['/broadcast/index'],
    'active' => $this->context->module->id == 'bradcast' ? true : false,
];
$items[] = [
    'label' => 'Шаблоны писем',
    'icon' => 'fa fa-circle-thin',
    'url' => ['/broadcast/layouts'],
    'active' => $this->context->module->id == 'layouts' ? true : false,
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
