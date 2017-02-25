<?php
use yii\bootstrap\Html;
use kartik\form\ActiveForm;
use yii\bootstrap\Tabs;
use backend\widgets\ckeditor\CKEditor;
use yii\bootstrap\Modal;

/**
 * @var $this \yii\web\View
 * @var $auctionmbLot \common\models\auctionmb\AuctionmbLotForm
 * @var $link \common\models\main\Links
 * @var $galleryImage \common\models\gallery\GalleryImagesForm
 */

$this->title = 'Управление лотом';
$this->params['breadcrumbs'][] = ['label' => 'Все лоты', 'url' => 'index'];
$this->params['breadcrumbs'][] = $this->title;

$linkClose = ['index'];

$imageSmallLabel = '';
if ($galleryImage->small) {
    $imageSmallLabel = Html::beginTag('div', ['id' => 'image-small-preview']) .
        Html::button('Удалить', [
            'class' => 'btn btn-default btn-sm btn-flat',
            'onclick' => '$(".image-small").val(""); $("#image-small-preview").fadeOut();'
        ]) .
        Html::img($galleryImage->small) .
        Html::endTag('div');
}

$imageLargeLabel = '';
if ($galleryImage->large) {
    $imageLargeLabel = Html::beginTag('div', ['id' => 'image-large-preview']) .
        Html::button('Удалить', [
            'class' => 'btn btn-default btn-sm btn-flat',
            'onclick' => '$(".image-large").val(""); $("#image-large-preview").fadeOut();'
        ]) .
        Html::img($galleryImage->large) .
        Html::endTag('div');
}
?>

<div class="row">
    <div class="col-md-8">

        <div class="box box-primary">
            <div class="box-body">

                <?php $form=ActiveForm::begin(); ?>

                <div class="nav-tabs-custom">
                    <?php echo Tabs::widget([
                        'items' => [
                            [
                                'label' => 'Основные параметры',
                                'content' => Html::beginTag('p') .
                                    $form->field($link, 'state')->checkbox() .
                                    $form->field($auctionmbLot, 'seconds') .
                                    $form->field($auctionmbLot, 'bets') .
                                    $form->field($link, 'anchor')->label('Наименование лота') .
                                    $form->field($link, 'title')->label('Заголовок страницы (опционально)') .
                                    Html::endTag('p'),
                                'active' => true
                            ],
                            [
                                'label' => 'Медиа',
                                'content' => Html::beginTag('p') .
                                    $form->field($galleryImage, 'id')->hiddenInput()->label(false) .
                                    $form->field($galleryImage, 'small')->hiddenInput(['class' => 'form-control image-small'])->label(false) .
                                    $form->field($galleryImage, 'large')->hiddenInput(['class' => 'form-control image-large'])->label(false) .
                                    $form->field($galleryImage, 'imageSmall')->fileInput()->label(
                                        $galleryImage->getAttributeLabel('imageSmall') .
                                        ' ('.$galleryImage->galleryGroup->galleryType->small_width.'x'.$galleryImage->galleryGroup->galleryType->small_height.'px)' .
                                        $imageSmallLabel) .
                                    $form->field($galleryImage, 'imageLarge')->fileInput()->label(
                                        $galleryImage->getAttributeLabel('imageLarge') .
                                        ' ('.$galleryImage->galleryGroup->galleryType->large_width.'x'.$galleryImage->galleryGroup->galleryType->large_height.'px)' .
                                        $imageLargeLabel) .
                                    Html::endTag('p'),
                            ],
                            [
                                'label' => 'Контент',
                                'content' => Html::beginTag('p') .
                                    $form->field($auctionmbLot, 'text')->widget(CKEditor::className(), [
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
                                    ]) .
                                    Html::endTag('p'),
                            ],
                            [
                                'label' => 'Дополнительно (системные параметры)',
                                'content' => Html::beginTag('p') .
                                    $form->field($link, 'url') .
                                    $form->field($link, 'name') .
                                    $form->field($link, 'seq')->label('Порядковый номер') .
                                    Html::endTag('p'),
                            ],
                            [
                                'label' => 'Параметры SEO',
                                'content' => Html::beginTag('p') .
                                    $form->field($link, 'keywords')->textarea() .
                                    $form->field($link, 'description')->textarea() .
                                    $form->field($link, 'priority') .
                                    Html::endTag('p'),
                            ]
                        ],
                    ])?>
                </div>

                <div class="row">
                </div>

                <?= Html::a('Отмена', $linkClose, ['class' => 'btn btn-default btn-sm btn-flat'])?>
                <?= Html::submitButton(($link->id ? 'Изменить' : 'Добавить'), [
                    'class' => 'btn btn-primary btn-flat btn-sm',
                    'name' => 'signup-button',
                ])?>
                <?php if ($link->id) {
                    Modal::begin([
                        'header' => $link->anchor.' '.Html::a('<i class="fa fa-external-link"></i>', $link->url, ['target' => '_blank']),
                        'toggleButton' => ['label' => 'Удалить', 'class' => 'btn btn-danger btn-flat btn-sm'],
                        'footer' => Html::a('Отмена', '#', ['data-dismiss' => 'modal', 'class' => 'btn btn-default btn-flat btn-sm']) .
                            Html::a('Удалить', ['lot-del', 'id' => $auctionmbLot->id], ['class' => 'btn btn-danger btn-flat btn-sm']),
                    ]);
                    echo '<p>Действительно удалить элемент?</p>';
                    Modal::end();
                }?>

                <?php ActiveForm::end(); ?>

            </div>
        </div>

    </div>
</div>