<?php
use app\modules\broadcast\BroadcastAssets;

/** @var $this \yii\web\View */

BroadcastAssets::register($this);


$items[] = ['label' => 'Email-рассылка', 'options' => ['class' => 'header']];
$items[] = [
    'label' => 'История отправок',
    'icon' => 'fa fa-circle-thin',
    'url' => ['/broadcast/index'],
    'active' => $this->context->module->id == 'bradcast' ? true : false,
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
