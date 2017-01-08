<?php
use kartik\form\ActiveForm;
use yii\bootstrap\Html;

/**
 * @var $this \yii\web\View
 * @var $smsContent \common\models\sms\SmsContent
 * @var $sendContacts \common\models\sms\SmsSendContacts
 * @var $model \app\modules\sms\models\RenderSendForm
 */

$this->title = 'Подготовка к отправке сообщения "'.$smsContent->comment.'"';

?>

<div class="row">
    <div class="col-md-7 col-sm-12">
        <div class="box box-default">
            <?php $form=ActiveForm::begin() ?>

            <div class="box-header with-border">
                <?= Html::submitButton('Отправить сообщение', ['class' => 'btn btn-primary btn-flat btn-sm']) ?>
                <?= Html::a('Снять все отметки', null, ['class' => 'btn btn-link btn-sm', 'onclick' => 'actionCheckbox($(this))']) ?>
            </div>
            <div class="box-body">
                <div>
                    <strong>Текст сообщения:</strong>
                    <p><?=$smsContent->content?></p>
                </div>

                <?php
                if ($sendContacts) {
                    $list = [];
                    /** @var \common\models\sms\SmsSendContacts $sendContact */
                    foreach ($sendContacts as $sendContact) {
                        $fio = '';
                        $fio = $sendContact->smsContact->surname ? $sendContact->smsContact->surname : '';
                        $fio .= $sendContact->smsContact->name ? ' '.$sendContact->smsContact->name : '';
                        $fio .= $sendContact->smsContact->patronymic ? ' '.$sendContact->smsContact->patronymic : '';
                        $list[$sendContact->id] = '+'.$sendContact->smsContact->phone . ($fio ? ', '.$fio : '');
                    }
                    echo $form->field($model, 'contact_ids')->checkboxList($list)->label('Сообщения для отправки:');
                }
                ?>

            </div>

            <?php ActiveForm::end() ?>
        </div>

    </div>
</div>
