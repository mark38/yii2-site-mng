<?php
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\bootstrap\Html;

/**
 * @var $this \yii\web\View
 * @var $profile \common\models\User;
 */

$this->title = 'Управление профилем';

?>

<div class="row">
    <div class="col-sm-6">
        <div class="box box-primary">
            <div class="box-body">
                <?php $form = ActiveForm::begin([
                    'type' => ActiveForm::TYPE_HORIZONTAL,
                    'formConfig' => ['labelSpan' => 4]
                ]); ?>

                <?= Form::widget([
                    'model' => $profile,
                    'form' => $form,
                    'columns' => 1,
                    'attributes' => [
                        'username' => ['type' => Form::INPUT_HIDDEN_STATIC],
                        'email' => ['type' => Form::INPUT_TEXT],
                        'password' => ['type' => Form::INPUT_PASSWORD],
                        'password_repeat' => ['type' => Form::INPUT_PASSWORD],
                    ]
                ])?>

                <div class="form-group"><div class="col-sm-offset-4 col-md-8"><?=Html::submitButton('Изменить', ['class' => 'btn btn-sm btn-primary btn-flat'])?></div></div>

                <?php ActiveForm::end();?>
            </div>
        </div>
    </div>
</div>
