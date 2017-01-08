<?php
use kartik\form\ActiveForm;
use yii\bootstrap\Html;

/**
 * @var $this \yii\web\View
 * @var $smsContent \common\models\sms\SmsContent
 * @var $sendContacts \common\models\sms\SmsSendContacts
 */

$this->title = 'Отправка сообщения "'.$smsContent->comment.'"';

$this->params['breadcrumbs'][] = ['label' => 'Сообщения', 'url' => 'index'];

?>

<div class="row">
    <div class="col-md-7 col-sm-12">
        <div class="box box-default">
            <div class="box-header with-border">
                <?= Html::tag('em', 'Идёт отправка', ['class' => 'text-info']) ?>
            </div>

            <div class="box-body">
                <div>
                    <strong>Текст сообщения:</strong>
                    <p><?=$smsContent->content?></p>
                </div>

                <ul class="list-unstyled">
                    <?php
                    if ($sendContacts) {
                        /** @var \common\models\sms\SmsSendContacts $sendContact */
                        foreach ($sendContacts as $sendContact) {
                            $fio = '';
                            $fio = $sendContact->smsContact->surname ? $sendContact->smsContact->surname : '';
                            $fio .= $sendContact->smsContact->name ? ' '.$sendContact->smsContact->name : '';
                            $fio .= $sendContact->smsContact->patronymic ? ' '.$sendContact->smsContact->patronymic : '';
                            echo Html::tag('li', '+'.$sendContact->smsContact->phone . ($fio ? ', '.$fio : '').' &mdash; '.Html::tag('em', 'Идёт отправка', ['class' => 'text-info']));
                        }
                    }
                    ?>
                </ul>

            </div>
        </div>

    </div>
</div>
