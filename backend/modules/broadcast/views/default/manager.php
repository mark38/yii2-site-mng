<?php
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\widgets\ckeditor\CKEditor;
use iutbay\yii2kcfinder\KCFinder;
use common\models\broadcast\BroadcastLayouts;
use kartik\file\FileInput;

/** @var $this \yii\web\View */
/** @var $broadcast \app\modules\broadcast\models\BroadcastForm */
/** @var $address \common\models\broadcast\BroadcastAddress */
/** @var $users \common\models\User */

$this->title = 'Email-рассылка';

$this->params['breadcrumbs'][] = ['label' => 'Рассылка', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Управление';

$kcfOptions = array_merge(KCFinder::$kcfDefaultOptions, [
    'uploadURL' => Yii::$app->params['hostname'].Yii::$app->params['uploadURL'],
    'uploadDir' => Yii::$app->params['uploadDir'],
    'access' => [
        'files' => [
            'upload' => true,
            'delete' => true,
            'copy' => true,
            'move' => true,
            'rename' => true,
        ],
        'dirs' => [
            'create' => true,
            'delete' => true,
            'rename' => true,
        ],
    ],
]);

Yii::$app->session->set('KCFINDER', $kcfOptions);
?>

<div class="row">
    <div class="col-sm-8">
        <div class="box box-primary">
            <div class="box-body">

                <?php $form = ActiveForm::begin([
                    'method' => 'post',
                ]); ?>

                <?=$form->field($broadcast, 'broadcast_layouts_id')->dropDownList(ArrayHelper::map(BroadcastLayouts::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'))?>
                <?=$form->field($broadcast, 'title')?>
                <?=$form->field($broadcast, 'h1')?>
                <?=$form->field($broadcast, 'content')->widget(CKEditor::className(), [
                    'preset' => 'full',
                    'clientOptions' => [
                        'height' => 300,
                        'toolbar' => [
                            [
                                'name' => 'row1',
                                'items' => [
                                    'Source', '-',
                                    'Bold', 'Italic', 'Underline', 'Strike', '-',
                                    'Subscript', 'Superscript', 'RemoveFormat', '-',
                                    'TextColor', 'BGColor', '-',
                                    'NumberedList', 'BulletedList', '-',
                                    'Outdent', 'Indent', '-', 'Blockquote', '-',
                                    'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'list', 'indent', 'blocks', 'align', 'bidi', '-',
                                    'Link', 'Unlink', 'Anchor', '-',
                                    'ShowBlocks', 'Maximize',
                                ],
                            ],
                            [
                                'name' => 'row2',
                                'items' => [
                                    'Image', 'Table', 'HorizontalRule', 'SpecialChar', 'Iframe', '-',
                                    'NewPage', 'Print', 'Templates', '-',
                                    'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-',
                                    'Undo', 'Redo', '-',
                                    'Find', 'SelectAll', 'Format', 'Font', 'FontSize',
                                ],
                            ],
                        ],
                    ],
                ])->label(false);?>

                <?=$form->field($broadcast, 'registered_users')->checkbox()?>
                <?=$form->field($broadcast, 'destinations')->textarea()?>

                <div class="form-group">
                    <?php if ($broadcast->id) {
                        echo FileInput::widget([
                            'name' => 'attach',
                            'options' => ['accept' => 'image/*, application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'multiple' => true, 'uploadAsync' => false, 'maxFileCount' => 1],
                            'pluginOptions' => [
                                'initialPreview' => ($broadcast->initialPreviewConfig ? ArrayHelper::getColumn($broadcast->initialPreviewConfig, 'file') : false),
                                'initialPreviewConfig' => $broadcast->initialPreviewConfig,

                                'initialPreviewAsData' => true,
                                'overwriteInitial' => false,
                                'showPreview' => true,
                                'showCaption' => false,
                                'showUpload' => false,
                                'showRemove' => false,
                                'showCancel' => false,
                                'dropZoneEnabled' => true,

                                'browseClass' => 'btn btn-default btn-sm btn-flat',
                                'removeClass' => 'btn btn-danger btn-sm btn-flat',

                                'uploadUrl' => Url::to(['/broadcast/file-upload']),
                                'uploadExtraData' => [
                                    'broadcast_id' => $broadcast->id,
                                    'name' => 'attach'
                                ],
                                'maxFileCount' => 20,
                                'uploadAsync' => true,
                                'uploadUrl' => Url::to(['/broadcast/file-manager/file-upload']),
                            ],
                            'pluginEvents' => [
                                'fileimagesloaded' => 'function(event) {
                        $(this).fileinput("upload");
                    }',
                            ]
                        ]);
                    }?>
                </div>

                <?=Html::a('Отмена', ['/broadcast/index'], ['class' => 'btn btn-sm btn-default btn-flat'])?>
                <?=Html::submitButton('Сохранить', [
                    'class' => 'btn btn-sm btn-primary btn-flat'
                ]);?>
                <?php if (isset($broadcast->id)) {
                    echo Html::a('Подготовить к отправке', ['/broadcast/render-send', 'broadcast_id' => $broadcast->id], ['class' => 'btn btn-sm btn-success btn-flat']);
                }?>

                <?php ActiveForm::end()?>

            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <h3>Паттерны в контенте и их расшифровка при наличии соответствоющих записей в базе данных</h3>
        <ul>
            <li>{{h1}} &mdash; Заголовок в теле письма;</li>
            <li>{{if}} &mdash; Имя Фамилия в именительном падеже;</li>
            <li>{{company}} &mdash; полное наименование компании;</li>
            <li>{{current_date}} &mdash; сегодняшняя дата в формат mm.dd.YYYY;</li>
        </ul>
        <hr>
        <h3>Список адресов указывается согласно следующего шаблона с разделителем ","</h3>
        <p>email[#Фамилия Имя Отчество]</p>
        <p><strong>Пример:</strong> iivanov@mail.ru#Иванов Иван,inof@domain.com,promo@site.ru#Борисов Дмитрий Фёдорович,director@company.ru#Пётр</p>
        <h3>Как прикрепить файлы к письму</h3>
        <p>При добавлении нового задания имаил-рассылки возможность прикрипляния файлов к письму появиться после заполнении формы и нажатия "Сохранить".</p>
    </div>
</div>
