<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\bootstrap\Modal;
use kartik\builder\Form;

/**
 * @var $user \common\models\User;
 * @var $employees array
 */

if(Yii::$app->request->get('name')) {
    $title = 'Редактирование группы '.$role->name;
    $button = 'Сохранить';
} else {
    $title = 'Добавление новой группы';
    $button = 'Добавить';
}
?>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $title ?></h3>
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>
    <div class="box-body">
        <?php $form = ActiveForm::begin([
            'type' => ActiveForm::TYPE_HORIZONTAL,
            'formConfig' => ['labelSpan' => 2]
        ]); ?>

        <?= $form->field($role, 'name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($role, 'description')->textInput(['maxlength' => true]) ?>

        <?= Html::submitButton('<i class="fa fa-check"></i> '.$button, [
            'class' => 'btn btn-sm btn-flat btn-primary',
            'name' => 'send-button',
            'value' => 'Добавить',
        ]).' ';
        if (Yii::$app->request->get('name')) {
            Modal::begin([
                'header' => '<h2>Действительно удалить группу из списка?</h2>',
                'toggleButton' => [
                    'label' => '<span class="fa fa-times"></span> Удалить',
                    'encodeLabels' => false,
                    'tag' => 'button',
                    'class' => 'btn btn-sm btn-flat btn-danger'
                ]
            ]);
            echo '<div class="text-center"><ul class="list-inline">' .
                '<li>'.Html::a('<span class="fa fa-times"></span> Удалить', '/mng/settings/del-role?name='.Yii::$app->request->get('name'), ['class' => 'btn btn-sm btn-flat btn-danger']).'</li>' .
                '<li>'.Html::a('Отменить', '', ['class' => 'btn btn-sm btn-flat btn-default ', 'data-dismiss' => "modal", 'aria-hidden' => true]).'</li>' .
                '</ul></div>';
            Modal::end();
        }

        ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>







