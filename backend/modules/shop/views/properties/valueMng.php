<?php
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use backend\widgets\ckeditor\CKEditor;
use iutbay\yii2kcfinder\KCFinder;
use mark38\galleryManager\GalleryManager;

/** @var $value \common\models\shop\ShopPropertyValues */
/** @var \common\models\main\Contents $content */

$link_close = ['properties_id' => Yii::$app->request->get('properties_id'), 'action' => 'get_values'];
?>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Управление значением свойства</h3>
        <div class="box-tools pull-right">
            <?=Html::a('<i class="fa fa-times"></i>', $link_close, ['class' => 'btn btn-box-tool'])?>
        </div>
    </div>
    <div class="box-body">
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
                ]
            ]
        ])?>

        <?=$form->field($value, 'name')?>
        <?=$form->field($value, 'anchor')?>
        <?=$form->field($value, 'url')?>
        <?=$form->field($value, 'gallery_images_id')->widget(GalleryManager::className(), [
            'group' => false,
            'gallery_groups_id' => 1,
            'pluginOptions' => [
                'type' => 'promo',
                'apiUrl' => 'gallery-manager',
                'webRoute' => Yii::getAlias('@frontend/web'),
            ]
        ])?>
        <?=$form->field($content, 'text', [
            'template' => '{label}<div class="col-sm-12">{input}</div><div class="col-sm-10">{error}</div>',
            'labelOptions' => ['class' => 'col-sm-12']
        ])->widget(CKEditor::className(), [
            'preset' => 'full',
            'clientOptions' => [
                'height' => 180,
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
        ])?>

        <div class="hide"><?=$form->field($value, 'shop_properties_id')->hiddenInput()?></div>

        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-6">
                <?= Html::a('Отмена', $link_close, ['class' => 'btn btn-default btn-sm btn-flat'])?>

                <?= Html::submitButton(($value->id ? 'Изменить' : 'Добавить'), [
                    'class' => 'btn btn-primary btn-flat btn-sm',
                    'name' => 'signup-button',
                    'value' => 'Добавить',
                ])?>

                <?php if ($value->id) {
                    echo Html::a('Ретакторовать описание', Url::to(['contentMng', 'properties_id' => $value->id]), [
                            'class' => 'btn btn-info btn-flat btn-sm'
                        ]).'&nbsp;';
                    Modal::begin([
                        'header' => $value->name,
                        'toggleButton' => ['label' => 'Удалить', 'class' => 'btn btn-danger btn-flat btn-sm'],
                        'footer' => Html::a('Отмена', '#', ['data-dismiss' => 'modal', 'class' => 'btn btn-default btn-flat btn-sm']) .
                                    Html::a('Удалить', ['value-del', 'properties_id' => $value->shop_properties_id, 'values_id' => $value->id], ['class' => 'btn btn-danger btn-flat btn-sm']),
                    ]);
                    echo '<p>Значение свойства будет удалено со всем содержимым, в том числе описание.</p><p>Действительно удалить значение?</p>';
                    Modal::end();
                }?>
            </div>
        </div>

        <?php ActiveForm::end();?>
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