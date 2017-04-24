<?php
use kartik\helpers\Html;
use kartik\form\ActiveForm;
use backend\widgets\ckeditor\CKEditor;
use kartik\builder\Form;
use yii\bootstrap\Tabs;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use common\models\shop\ShopProperties;
use yii\helpers\Url;

/**
 * @var $this \yii\web\View
 * @var $action
 * @var $link \backend\modules\shop\models\LinkGroupForm
 * @var $good \backend\modules\shop\models\GoodForm
 * @var $galleryImage \common\models\gallery\GalleryImagesForm
 */

$linkClose = ['/shop/products/links'];

$imageSmallLabel = '';
if ($galleryImage->small) {
    $imageSmallLabel = Html::beginTag('div', ['id' => 'image-small-preview']) .
        Html::button('Удалить', [
            'class' => 'btn btn-default btn-sm btn-flat',
            'onclick' => '$(".image-small").val(""); $("#image-small-preview").fadeOut();'
        ]) .
        '<br>' .
        Html::img($galleryImage->small, ['style' => 'max-width:60%;']) .
        Html::endTag('div');
}

$imageLargeLabel = '';
if ($galleryImage->large) {
    $imageLargeLabel = Html::beginTag('div', ['id' => 'image-large-preview']) .
        Html::button('Удалить', [
            'class' => 'btn btn-default btn-sm btn-flat',
            'onclick' => '$(".image-large").val(""); $("#image-large-preview").fadeOut();'
        ]) .
        '<br>' .
        Html::img($galleryImage->large, ['style' => 'max-width:80%;']) .
        Html::endTag('div');
}
?>

<div class="box box-default">
    <div class="box-header with-border">
        <h3>
            <?php switch ($action) {
                case "add": echo 'Новая номенклатура'; break;
                case "ch": echo 'Редактирование номенклатуры "'.$good->name.'"'; break;
            }?>
        </h3>
        <div class="box-tools pull-right"><?=Html::a('<i class="fa fa-times"></i>', $linkClose, ['class' => 'btn btn-box-tool'])?></div>
    </div>

    <div class="box-body">
        <?php $form = ActiveForm::begin([
            'type' => ActiveForm::TYPE_HORIZONTAL,
            'formConfig' => ['labelSpan' => 4]
        ]); ?>

        <div class="nav-tabs-custom">
            <?php
            echo Tabs::widget([
                'items' => [
                    [
                        'label' => 'Основное',
                        'content' => Html::beginTag('p') .
                            $form->field($link, 'state')->checkbox() .
                            $form->field($link, 'anchor') .
                            $form->field($good, 'shop_units_id')->dropDownList($good->units, ['encode' => false]) .
                            $form->field($good, 'code') .

                            Html::tag('legend', '<small>Стоимость</small>', ['class' => 'text-muted']) .
                            $this->render('goodPricesForm', compact('link', 'good', 'form')) .

                            Html::tag('legend', '<small>Дополнительные параметры</small>', ['class' => 'text-muted']) .
                            $form->field($link, 'name') .
                            $form->field($link, 'url') .

                            Html::tag('legend', '<small>SEO</small>', ['class' => 'text-muted']) .
                            $form->field($link, 'title') .
                            $form->field($link, 'h1') .
                            $form->field($link, 'description')->textarea() .
                            $form->field($link, 'keywords') .
                            Html::endTag('p'),
                        'active' => true
                    ],
                    [
                        'label' => 'Контент',
                        'content' => Html::beginTag('p') .
                            $form->field($link, 'text')->widget(CKEditor::className(), [
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
                            ])->label(false) .
                            Html::endTag('p'),
                    ],
                    [
                        'label' => 'Медиа',
                        'content' => Html::beginTag('p') .
                            '<div clas="hidden">' .
                            $form->field($galleryImage, 'id')->hiddenInput()->label(false) .
                            $form->field($galleryImage, 'small')->hiddenInput(['class' => 'form-control image-small'])->label(false) .
                            $form->field($galleryImage, 'large')->hiddenInput(['class' => 'form-control image-large'])->label(false) .
                            '</div>' .
                            $form->field($galleryImage, 'imageSmall')->fileInput()->label(
                                $galleryImage->getAttributeLabel('imageSmall') .
                                $imageSmallLabel) .
                            $form->field($galleryImage, 'imageLarge')->fileInput()->label(
                                $galleryImage->getAttributeLabel('imageLarge') .
                                $imageLargeLabel) .
                            Html::endTag('p'),
                    ],
                    [
                        'label' => 'Значения свойств',
                        'content' => Html::tag('p', $this->render('goodPropertiesForm', compact('link', 'good', 'form'))),
                    ],
                ]
            ]);
            ?>
        </div>

        <?= Html::a('Закрыть', $linkClose, ['class' => 'btn btn-default btn-sm btn-flat'])?>

        <?= Html::submitButton(($link->id ? 'Изменить' : 'Добавить'), [
            'class' => 'btn btn-primary btn-flat btn-sm',
            'name' => 'signup-button',
            'value' => 'Добавить',
        ])?>

        <?php if ($link->id) {
            Modal::begin([
                'header' => $link->anchor.' '.Html::a('<i class="fa fa-external-link"></i>', $link->url, ['target' => '_blank']),
                'toggleButton' => ['label' => 'Удалить', 'class' => 'btn btn-danger btn-flat btn-sm'],
                'footer' => Html::a('Отмена', '#', ['data-dismiss' => 'modal', 'class' => 'btn btn-default btn-flat btn-sm']) .
                    Html::a('Удалить', ['/map/link-del', 'id' => $link->id], ['class' => 'btn btn-danger btn-flat btn-sm']),
            ]);
            echo '<p>Запись будет удалена со всем содержимым, в том числе контент страницы.</p><p>Действительно удалить номенклатуру?</p>';
            Modal::end();
        }?>

        <?php ActiveForm::end()?>
    </div>
</div>
