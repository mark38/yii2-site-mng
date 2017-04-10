<?php
/** @var $this \yii\web\View */

$items[] = ['label' => 'Заполненные формы', 'options' => ['class' => 'header']];
$formTypes = $this->context->formTypes;

foreach ($formTypes as $formType) {
    $items[] = [
        'label' => $formType->name,
        'icon' => 'fa fa-id-card-o',
        'url' => ['/forms/form', 'type_id' => $formType->id],
        'active' => Yii::$app->request->get('id') == $formType->id ? true : false,
    ];
}


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
