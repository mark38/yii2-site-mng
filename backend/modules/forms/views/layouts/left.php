<?php
/** @var $this \yii\web\View */

$items[] = ['label' => 'Формы', 'options' => ['class' => 'header']];
$items[] = [
    'label' => 'Заполненные формы',
    'icon' => 'fa fa-id-card-o',
    'url' => ['/forms/index'],
    'active' => $this->context->module->id == 'forms' ? true : false,
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
