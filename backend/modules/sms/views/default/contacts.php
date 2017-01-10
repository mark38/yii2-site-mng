<?php
use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/** @var $this \yii\web\View */
/** @var $contacts \common\models\sms\SmsContacts */

$this->title = 'Список контактов для SMS-рассылок';

?>

<div class="row">
    <div class="col-md-7 col-sm-12">

        <div class="box box-default">
            <div class="box-header with-border">
                <?=Html::submitButton('Добавить', [
                    'class' => 'btn btn-primary btn-flat btn-sm',
                    'onclick' => 'formContact()'
                ])?>

                <?=Html::a('<i class="fa fa-file-excel-o" aria-hidden="true"></i> Импорт', null, [
                    'class' => 'btn btn-success btn-flat btn-sm',
                    'id' => 'action-contacts-upload',
                    'data-url' => Url::to(['contacts-upload']),
                    'onclick' => 'formUploadContacts()',
                ])?>

                <?=Html::a('<i class="fa fa-file-excel-o" aria-hidden="true"></i> Экспорт', ['contacts-load'], [
                    'class' => 'btn btn-default btn-flat btn-sm',
                    'target' => '_blank'
                ])?>

                <div class="box-tools pull-right">
                    <div class="has-feedback">
                        <input type="text" class="form-control input-sm" placeholder="Поиск...">
                        <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <table class="table table-hover table-condensed">
                    <thead>
                    <tr><th>#</th><th>Телефон</th><th>ФИО</th><th>Пол</th><th>Отдравлять сообщение</th><th class="text-right">Действие</th></tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($contacts) {
                        foreach ($contacts as $i => $contact) {
                            echo $this->render('contact', ['contact' => $contact, 'num' => ($i+1)]);
                        }
                    } else {
                        echo '<tr><td colspan="6" class="text-center"><em class="text-muted">Контакты не заданы</em></td></tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<?php
Modal::begin([
    'header' => Html::tag('div', '', ['class' => 'header-content']),
    'footer' => Html::button('', ['data-dismiss' => 'modal', 'class' => 'btn btn-default btn-flat btn-sm btn-close']) .
                Html::submitButton('', [
                    'class' => 'btn btn-primary btn-flat btn-sm btn-action',
                    'id' => 'contact-action',
                    'onclick' => ''
                ]),
    'options' => [
        'class' => 'modal-preview fade',
        'id' => 'modal-contact',
        'data-sms-contacts-id' => false,
        'data-url' => Url::to(['contact']),
    ]
]);

Modal::end();
?>
