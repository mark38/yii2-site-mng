<?php
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\widgets\ckeditor\CKEditor;
use iutbay\yii2kcfinder\KCFinder;
use common\models\broadcast\BroadcastLayouts;

/** @var $this \yii\web\View */
/** @var $broadcast \common\models\broadcast\Broadcast */
/** @var $address \common\models\broadcast\BroadcastAddress */
/** @var $users \common\models\User */

$this->title = 'Рассылка уведомлений';

$this->params['breadcrumbs'][] = ['label' => 'Рассылка', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Управление';

$kcfOptions = array_merge(KCFinder::$kcfDefaultOptions, [
    'uploadURL' => Yii::$app->params['hostname'].'/uploads',
    'uploadDir' => Yii::getAlias('@frontend/web/uploads'),
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

<h1><?=$this->title?></h1>

<?php $form = ActiveForm::begin([
    'method' => 'post',
]); ?>

<div class="row">
    <div class="col-sm-8">
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

        <?=Html::a('Отмена', ['/broadcast/index'], ['class' => 'btn btn-sm btn-default'])?>
        <?=Html::submitButton('Сохранить', [
            'class' => 'btn btn-sm btn-primary'
        ]);?>
        <?php if (isset($broadcast->id)) {
            echo Html::a('Подготовить к отправке', ['/broadcast/render-send', 'broadcast_id' => $broadcast->id], ['class' => 'btn btn-sm btn-success']);
        }?>
    </div>
    <div class="col-sm-4">
        <h3>Паттерны в контенте и их расшифровка</h3>
        <ul>
            <li>{{h1}} &mdash; Заголовок в теле письма;</li>
            <li>{{fi}} &mdash; Фамилия Имя в именительном падеже;</li>
            <li>{{company}} &mdash; полное наименование компании;</li>
            <li>{{current_date}} &mdash; сегодняшняя дата в формат mm.dd.YYYY;</li>
            <li>... по запросу.</li>
        </ul>
        <hr>
        <h3>Список адресов указывается согласно следующего шаблона с разделителем ","</h3>
        <p>email[#Фамилия Имя Отчество]</p>
        <p><strong>Пример:</strong> iivanov@mail.ru#Иванов Иван,inof@domain.com,promo@site.ru#Борисов Дмитрий Фёдорович,director@company.ru#Пётр</p>
    </div>
</div>

<?php ActiveForm::end()?>
