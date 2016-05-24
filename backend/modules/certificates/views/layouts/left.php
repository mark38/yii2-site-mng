<?php
use app\modules\certificates\CertificatesAssets;

/** @var $this \yii\web\View */

CertificatesAssets::register($this);


$items[] = ['label' => 'Обмен справками РЖД', 'options' => ['class' => 'header']];
$items[] = [
    'label' => 'Список задач',
    'icon' => 'fa fa-circle-thin',
    'url' => ['/certificates/index'],
    'active' => Yii::$app->controller->action->id == 'index' ? true : false,
];
$items[] = [
    'label' => 'Перечень справок',
    'icon' => 'fa fa-circle-thin',
    'url' => ['/certificates/list'],
    'active' => Yii::$app->controller->action->id == 'list' ? true : false,
];
$items[] = [
    'label' => 'Список компаний',
    'icon' => 'fa fa-circle-thin',
    'url' => ['/certificates/companies'],
    'active' => Yii::$app->controller->action->id == 'companies' ? true : false,
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
