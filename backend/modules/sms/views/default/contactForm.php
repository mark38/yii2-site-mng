<?php
use kartik\form\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap\Html;

/** @var $this \yii\web\View */
/** @var $contact \common\models\sms\SmsContacts */

?>

<div>
    <?php $form = ActiveForm::begin([
        'id' => 'form-contact',
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => [
            'labelSpan' => 4
        ],
        'options' => [
            'data-url' => ($contact->id ? Url::to(['contact-mng', 'id' => $contact->id]) : Url::to(['contact-mng'])),
        ],
    ])?>

    <?=$form->field($contact, 'phone', ['addon' => ['prepend' => ['content' => '+']]])->textInput(['placeholder' => '79201234567'])?>
    <?=$form->field($contact, 'surname')?>
    <?=$form->field($contact, 'name')?>
    <?=$form->field($contact, 'patronymic')?>
    <?=$form->field($contact, 'gender')->dropDownList($contact->genders)?>
    <?=$form->field($contact, 'state')->checkbox()?>
    <div class="hide"><?=$form->field($contact, 'id')->hiddenInput()?></div>

    <?php
    if (!Yii::$app->request->isAjax) {
        echo Html::submitButton('Отправить');
    }
    ?>

    <?php ActiveForm::end(); ?>
</div>
