<?php
/** @var $this \yii\web\View */

$items[] = [
    'label' => 'Сообщения',
    'icon' => 'fa fa-circle-thin',
    'url' => ['/sms/index'],
];
$items[] = [
    'label' => 'Список контактов',
    'icon' => 'fa fa-circle-thin',
    'url' => ['/sms/contacts'],
];

$items[] = ['label' => 'Поддержка', 'options' => ['class' => 'header']];
$items[] = [
    'label' => 'Важно',
    'icon' => 'fa fa-life-ring',
    'url' => ['/sms/support/readme'],
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
