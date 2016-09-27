<?php
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use backend\widgets\ckeditor\CKEditor;
use iutbay\yii2kcfinder\KCFinder;
use yii\bootstrap\Modal;

/** @var $this \yii\web\View */
/** @var $layout \common\models\broadcast\BroadcastLayouts */

$this->title = 'Управление шаблоном';
$this->params['breadcrumbs'][] = ['label' => 'Шаблоны писем', 'url' => ['/broadcast/layouts']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-sm-8">
        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'layout' => 'horizontal',
            'fieldConfig' => [
                'horizontalCssClasses' => [
                    'label' => 'col-sm-4',
                    'offset' => 'col-sm-offset-4',
                    'wrapper' => 'col-sm-5',
                    'error' => '',
                    'hint' => '',
                ],
            ],
        ]); ?>

        <?=$form->field($layout, 'name')?>

        <?=$form->field($layout, 'layout_path')->textInput(['placeholder' => 'broadcast/default.php'])?>

        <?=$form->field($layout, 'content', [
            'template' => '{label}<div class="col-sm-12">{input}</div><div class="col-sm-10">{error}</div>',
            'labelOptions' => ['class' => 'col-sm-12']
        ])->widget(CKEditor::className(), [
            'options' => ['id' => 'full-text'],
            'preset' => 'full',
            'clientOptions' => [
                'height' => 300,
                'toolbar' => [
                    [
                        'name' => 'row1',
                        'items' => [
                            'Maximize', 'Source', '-',
                            'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-',
                            'Bold', 'Italic', 'Underline', 'Strike', '-',
                            'Subscript', 'Superscript', 'RemoveFormat', '-',
                            'TextColor', 'BGColor', '-',
                            'NumberedList', 'BulletedList', '-',
                            'Outdent', 'Indent', '-', 'Blockquote', '-',
                            'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'list', 'indent', 'blocks', 'align', 'bidi', '-',
                        ],
                    ],
                    [
                        'name' => 'row2',
                        'items' => [
                            'Link', 'Unlink', 'Anchor', '-',
                            'ShowBlocks', '-',
                            'Image', 'Table', 'HorizontalRule', 'SpecialChar', 'Iframe', '-',
                            'NewPage', 'Print', 'Templates', '-',
                            'Undo', 'Redo', '-',
                            'Find', 'SelectAll', 'Format', 'Font', 'FontSize',
                        ],
                    ],
                ],
            ],
            'clientOptions' => ['config.extraPlugins' => 'codeSnippet']
        ])?>

        <?= Html::a('Отмена', ['/boradcast/layouts'], ['class' => 'btn btn-default btn-sm btn-flat'])?>
        <?= Html::submitButton(($layout->id ? 'Изменить' : 'Добавить'), [
            'class' => 'btn btn-primary btn-flat btn-sm',
            'name' => 'signup-button',
        ])?>
        <?php if ($layout->id) {
            Modal::begin([
                'header' => $layout->name,
                'toggleButton' => ['label' => 'Удалить', 'class' => 'btn btn-danger btn-flat btn-sm'],
                'footer' => Html::a('Отмена', '#', ['data-dismiss' => 'modal', 'class' => 'btn btn-default btn-flat btn-sm']) .
                    Html::a('Удалить', ['news-del', 'links_id' => $layout->id], ['class' => 'btn btn-danger btn-flat btn-sm']),
            ]);
            echo '<p>Действительно удалить новость?</p>';
            Modal::end();
        }?>

        <?php ActiveForm::end()?>
    </div>
    <div class="col-sm-4">
        <h3>Паттерны в шаблоне и их расшифровка при наличии соответствоющих записей в базе данных</h3>
        <ul>
            <li>{{content}} &mdash; текст сообщения</li>
        </ul>
    </div>
</div>

<?php
// kcfinder options
// http://kcfinder.sunhater.com/install#dynamic
$kcfOptions = array_merge(KCFinder::$kcfDefaultOptions, [
    'uploadURL' => '/upload',
    'uploadDir' => Yii::getAlias('@frontend/web/upload'),
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

// Set kcfinder session options
Yii::$app->session->set('KCFINDER', $kcfOptions);
?>


