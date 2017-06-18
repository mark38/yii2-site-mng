<?php
use yii\bootstrap\Html;
use kartik\form\ActiveForm;
use yii\bootstrap\Tabs;
use yii\bootstrap\Modal;
use backend\widgets\ckeditor\CKEditor;
use yii\helpers\ArrayHelper;

/**
 * @var $this \yii\web\View
 * @var $itemType \common\models\items\ItemTypes
 * @var $itemTypes \common\models\items\ItemTypes
 * @var $item \app\modules\items\models\ItemForm
 * @var $galleryImage \common\models\gallery\GalleryImagesForm
 */

$this->title = 'Редактирование элемента';
$this->params['breadcrumbs'][] = ['label' => 'Все элементы', 'url' => 'index'];
$this->params['breadcrumbs'][] = ['label' => $itemType->name, 'url' => ['index', 'item_types_id' => $itemType->id]];

$linkClose = ['index', 'item_types_id' => $itemType->id];

$imageSmallLabel = '';
if ($galleryImage && $galleryImage->small) {
    $imageSmallLabel = Html::beginTag('div', ['id' => 'image-small-preview']) .
        Html::button('Удалить', [
            'class' => 'btn btn-default btn-sm btn-flat',
            'onclick' => '$(".image-small").val(""); $("#image-small-preview").fadeOut();'
        ]) .
        Html::img($galleryImage->small, ['style' => 'max-width: 100%;']) .
        Html::endTag('div');
}

$imageLargeLabel = '';
if ($galleryImage && $galleryImage->large) {
    $imageLargeLabel = Html::beginTag('div', ['id' => 'image-large-preview']) .
        Html::button('Удалить', [
            'class' => 'btn btn-default btn-sm btn-flat',
            'onclick' => '$(".image-large").val(""); $("#image-large-preview").fadeOut();'
        ]) .
        Html::img($galleryImage->large, ['style' => 'max-width: 100%;']) .
        Html::endTag('div');
}
?>

<div class="row">
    <div class="col-sm-8">

        <div class="box box-default">
            <div class="box-body">

                <?php $form = ActiveForm::begin()?>

                <div class="nav-tabs-custom">
                    <?php echo Tabs::widget([
                        'items' => [
                            [
                                'label' => 'Основные параметры',
                                'content' => Html::beginTag('p') .
                                    $form->field($item, 'state')->checkbox() .
                                    $form->field($item, 'item_types_id')->dropDownList(ArrayHelper::map($itemTypes, 'id', 'name')) .
                                    $form->field($item, 'name') .
                                    $form->field($item, 'title') .
                                    $form->field($item, 'url') .
                                    $form->field($item, 'price') .
                                    $form->field($item, 'old_price') .
                                    $form->field($item, 'seq') .
                                    Html::endTag('p'),
                                'active' => true
                            ],
                            [
                                'label' => 'Контент',
                                'content' => Html::beginTag('p') .
                                    $form->field($item, 'text')->widget(CKEditor::className(), [
                                        'options' => ['id' => 'text'],
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
                                    ]) .
                                    Html::endTag('p'),
                            ],
                            ($itemType->gallery_groups_id ? [
                                'label' => 'Медиа',
                                'content' => Html::beginTag('p') .
                                    $form->field($galleryImage, 'id')->hiddenInput()->label(false) .
                                    $form->field($galleryImage, 'small')->hiddenInput(['class' => 'form-control image-small'])->label(false) .
                                    $form->field($galleryImage, 'large')->hiddenInput(['class' => 'form-control image-large'])->label(false) .
                                    $form->field($galleryImage, 'imageSmall')->fileInput()->label(
                                        $galleryImage->getAttributeLabel('imageSmall') .
                                        ' ('.$itemType->galleryType->small_width.'x'.$itemType->galleryType->small_height.'px)' .
                                        $imageSmallLabel) .
                                    $form->field($galleryImage, 'imageLarge')->fileInput()->label(
                                        $galleryImage->getAttributeLabel('imageLarge') .
                                        ' ('.$itemType->galleryType->large_width.'x'.$itemType->galleryType->large_height.'px)' .
                                        $imageLargeLabel) .
                                    Html::endTag('p'),
                            ] : ['label' => false]),
                        ]
                    ])?>
                </div>

                <?= Html::a('Отмена', $linkClose, ['class' => 'btn btn-default btn-sm btn-flat'])?>
                <?= Html::submitButton(($item->id ? 'Изменить' : 'Добавить'), [
                    'class' => 'btn btn-primary btn-flat btn-sm',
                    'name' => 'signup-button',
                ])?>
                <?php if ($item->id) {
                    Modal::begin([
                        'header' => $item->name,
                        'toggleButton' => ['label' => 'Удалить', 'class' => 'btn btn-danger btn-flat btn-sm'],
                        'footer' => Html::a('Отмена', '#', ['data-dismiss' => 'modal', 'class' => 'btn btn-default btn-flat btn-sm']) .
                            Html::a('Удалить', ['item-del', 'items_id' => $item->id], ['class' => 'btn btn-danger btn-flat btn-sm']),
                    ]);
                    echo '<p>Действительно удалить элемент?</p>';
                    Modal::end();
                }?>

                <?php ActiveForm::end()?>
            </div>
        </div>

    </div>
</div>
