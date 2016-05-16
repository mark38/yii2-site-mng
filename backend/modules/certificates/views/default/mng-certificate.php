<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var $new \common\models\certificates\Certificates;
 * @var $certificate \common\models\certificates\Certificates;
 */
$title = $new ? 'Добавление новой справки' : 'Редактирование справки '.$certificate->code;
?>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $title ?></h3>
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>
    <div class="box-body">
        <?php
        $form = ActiveForm::begin([
            'method' => 'post',
            'ajaxDataType' => 'json',
            'layout' => 'horizontal',
        ]);

        echo $form->field($certificate, 'code')->textInput();
        echo $form->field($certificate, 'name')->textInput();
        echo $form->field($certificate, 'state')->dropDownList([1 => 'Активна', 0 => 'Неактивна']);

        echo Html::submitButton(('Сохранить'), [
            'class' => 'btn btn-primary btn-flat btn-sm',
            'name' => 'signup-button'
        ]);

        ActiveForm::end();
        ?>
    </div>
</div>