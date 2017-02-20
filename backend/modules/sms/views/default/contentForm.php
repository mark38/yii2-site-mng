<?php
use kartik\form\ActiveForm;
use yii\bootstrap\Html;
use kartik\checkbox\CheckboxX;

/** @var $this \yii\web\View */
/** @var $content \backend\modules\sms\models\SmsContentForm */
/** @var $contactsCount */

$this->title = 'Текст сообщения';

$this->params['breadcrumbs'][] = ['label' => 'Сообщения', 'url' => 'index'];

print_r($content);

?>

<div class="row">
    <div class="col-md-7 col-sm-12">

        <div class="box box-default">
            <div class="box-body">
                <?php $form=ActiveForm::begin() ?>

                <?=$form->field($content, 'comment')?>
                <?=$form->field($content, 'content')->textarea()->label($content->getAttributeLabel('content').' &mdash; <span>'.mb_strlen($content->content, 'utf-8').'</span> (без учёта подставляемых слов согласно паттернов)')->hint('1 СМС сообщение &mdash; до 70 русских / 160 латинских символов')?>
                <?=$form->field($content, 'contact_send')->checkbox(['label' => 'Подготовить к отправке всем клиентам из списка контактов ('.$contactsCount.')'])?>
                <?=$form->field($content, 'contacts')->textarea()?>

                <?=Html::a('Отмена', ['index'], ['class' => 'btn btn-default btn-flat btn-sm'])?>

                <?=Html::submitButton('Сохранить', ['class' => 'btn btn-primary btn-flat btn-sm'])?>

                <?php if ($content->id) echo Html::a('Подготовить к отправке', ['render-send', 'content_id' => $content->id], ['class' => 'btn btn-success btn-flat btn-sm']); ?>

                <?php ActiveForm::end() ?>
            </div>
        </div>

    </div>
    <div class="col-md-5">
        <strong>Паттерны в контенте и их расшифровка при наличии соответствоющих записей в базе данных</strong>
        <ul>
            <li>{{ИФ}} &mdash; Имя Фамилия в именительном падеже;</li>
            <li>{{ИО}} &mdash; Имя Отчество в именительном падеже;</li>
            <li>{{И}} &mdash; Имя в именительном падеже;</li>
            <li>{{Уважаемый}} &mdash; Уважаемый (ая) подставляется автоматически в зависимости от указанного клиента;</li>
        </ul>
        <hr>
        <strong>Список адресов указывается согласно следующего шаблона с разделителем ","</strong>
        <p>телефон[:Фамилия Имя Отчество[:м|ж]]</p>
        <p><strong>Пример:</strong> +79001234567#Иванов Иван#м,+79001234568,+79001234569#Борисов Дмитрий Фёдорович,+79001234560#Ирина</p>
    </div>
</div>
