<?php
use app\modules\certificates\CertificatesAssets;

/** @var $this \yii\web\View */

CertificatesAssets::register($this);


$items[] = ['label' => 'Обмен справками РЖД', 'options' => ['class' => 'header']];
$items[] = [
    'label' => 'Список задач',
    'icon' => 'fa fa-circle-thin',
    'url' => ['/certificates/index'],
    'active' => $this->context->module->id == 'certifies' ? true : false,
];
$items[] = [
    'label' => 'Перечень справок',
    'icon' => 'fa fa-circle-thin',
    'url' => ['/certificates/list'],
    'active' => $this->context->module->id == 'certifies' ? true : false,
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
