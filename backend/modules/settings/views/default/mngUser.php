<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\bootstrap\Modal;
use kartik\builder\Form;

/**
 * @var $user \common\models\User;
 * @var $employees array
 */
$isEdit = Yii::$app->request->get('id') ? true : false;
if($isEdit) {
    $title = 'Редактирование пользователя '.$user->username;
    $button = 'Сохранить';
} else {
    $title = 'Добавление нового пользователя';
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

        <?= Form::widget([
            'model' => $user,
            'form' => $form,
            'columns' => 1,
            'attributes' => [
                'username' => ['type' => Form::INPUT_TEXT],
                'email' => ['type' => Form::INPUT_TEXT],
                'password' => ['type' => Form::INPUT_PASSWORD],
                'password_repeat' => ['type' => Form::INPUT_PASSWORD],
            ]
        ])?>

        <?= $form->field($user, 'role')->dropDownList($roles)->label('Роль') ?>
        <?= $form->field($user, 'status')->dropDownList(['10' => 'Активен', '0' => 'Заблокирован']);?>

        <?= Html::submitButton('<i class="fa fa-check"></i> '.$button, [
            'class' => 'btn btn-sm btn-flat btn-primary',
            'name' => 'send-button',
            'value' => 'Добавить',
        ]).' ';
        if (Yii::$app->request->get('id')) {
            Modal::begin([
                'header' => '<h2>Действительно удалить пользователя из списка?</h2>',
                'toggleButton' => [
                    'label' => '<span class="fa fa-times"></span> Удалить',
                    'encodeLabels' => false,
                    'tag' => 'button',
                    'class' => 'btn btn-sm btn-flat btn-danger'
                ]
            ]);
            echo '<div class="text-center"><ul class="list-inline">' .
                '<li>'.Html::a('<span class="fa fa-times"></span> Удалить', '/settings/del-user?id='.Yii::$app->request->get('id'), ['class' => 'btn btn-sm btn-flat btn-danger']).'</li>' .
                '<li>'.Html::a('Отменить', '', ['class' => 'btn btn-sm btn-flat btn-default ', 'data-dismiss' => "modal", 'aria-hidden' => true]).'</li>' .
                '</ul></div>';
            Modal::end();
        }

        ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>







