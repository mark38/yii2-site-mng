<?php
use yii\bootstrap\Html;
use kartik\form\ActiveForm;
use yii\bootstrap\Tabs;
use yii\bootstrap\Modal;
use kartik\date\DatePicker;
use kartik\daterange\DateRangePicker;
use mark38\galleryManager\GalleryManager;
use backend\widgets\ckeditor\CKEditor;
use iutbay\yii2kcfinder\KCFinder;
use common\models\news\NewsTypes;

/**
 * @var $this \yii\web\View
 * @var $news \app\modules\news\models\NewsForm
 * @var $link \common\models\main\Links
 * @var $galleryImage \common\models\gallery\GalleryImages
 * @var $newsType NewsTypes
 */

$this->title = 'Редактирование нововсти';
$this->params['breadcrumbs'][] = ['label' => 'Все новости', 'url' => 'index'];

$linkClose = ['index'];

$imageSmallLabel = '';
if ($galleryImage->small) {
    $imageSmallLabel = Html::beginTag('div', ['id' => 'image-small-preview']) .
        Html::tag('div', Html::button('Удалить', [
            'class' => 'btn btn-default btn-sm btn-flat',
            'onclick' => '$(".image-small").val(""); $("#image-small-preview").fadeOut();'
        ])) .
        Html::img($galleryImage->small) .
        Html::endTag('div');
}

$imageLargeLabel = '';
if ($galleryImage->large) {
    $imageLargeLabel = Html::beginTag('div', ['id' => 'image-large-preview']) .
        Html::tag('div', Html::button('Удалить', [
            'class' => 'btn btn-default btn-sm btn-flat',
            'onclick' => '$(".image-large").val(""); $("#image-large-preview").fadeOut();'
        ])) .
        Html::img($galleryImage->large) .
        Html::endTag('div');
}
?>

<div class="row">
    <div class="col-md-7">

        <div class="box box-default">
            <div class="box-body">

                <?php $form=ActiveForm::begin(); ?>

                <div class="nav-tabs-custom">
                    <?php echo Tabs::widget([
                        'items' => [
                            [
                                'label' => 'Основные параметры',
                                'content' => Html::beginTag('p') .
                                    $form->field($link, 'state')->checkbox() .
                                    $form->field($news, 'news_types_id')->dropDownList($news->newsTypes) .
                                    $form->field($link, 'anchor')->label('Заголовок новости') .
                                    $form->field($link, 'title')->label('Заголовок страницы (опционально)') .
                                    $form->field($news, 'url')->label('Адрес внешней ссылки') .
                                    $form->field($news, 'date')->widget(DatePicker::className(), [
                                        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                                        'pluginOptions' => [
                                            'todayHighlight' => true,
                                            'todayBtn' => true,
                                            'autoclose' => true,
                                            'format' => 'dd.mm.yyyy'
                                        ],
                                        'options' => [
                                            'placeholder' => 'ДД.ММ.ГГГГ',
                                        ],
                                    ]) .
                                    $form->field($news, 'date_range')->widget(DateRangePicker::classname(), [
                                        'convertFormat' => true,
                                        'pluginOptions' => [
                                            'locale' => [
                                                'format' => 'd.m.Y',
                                                'separator' => ' - '
                                            ],
                                            'opens' => 'left'
                                        ],
                                    ]) .
                                    Html::endTag('p'),
                                'active' => true
                            ],
                            /*[
                                'label' => 'Контент',
                                'content' => Html::beginTag('p') .
                                    $form->field($news, 'prev_text')->textarea(['maxlength' => true, 'rows' => 2, 'id' => 'prev-text']) .
                                    $form->field($news, 'full_text')->widget(CKEditor::className(), [
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
                            ],*/
                            ($newsType->gallery_groups_id ? [
                                'label' => 'Баннер',
                                'content' => Html::beginTag('p') .
                                    $form->field($galleryImage, 'id')->hiddenInput()->label(false) .
                                    $form->field($galleryImage, 'small')->hiddenInput(['class' => 'form-control image-small'])->label(false) .
                                    $form->field($galleryImage, 'large')->hiddenInput(['class' => 'form-control image-large'])->label(false) .
                                    $form->field($galleryImage, 'imageSmall')->fileInput()->label($galleryImage->getAttributeLabel('imageSmall') . $imageSmallLabel) .
                                    $form->field($galleryImage, 'imageLarge')->fileInput()->label($galleryImage->getAttributeLabel('imageLarge') . $imageLargeLabel) .
                                    Html::endTag('p'),
                            ] : ''),
                            [
                                'label' => 'Дополнительно (системные параметры)',
                                'content' => Html::beginTag('p') .
                                    $form->field($link, 'url') .
                                    $form->field($link, 'name') .
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

                <?= Html::a('Отмена', $link_close, ['class' => 'btn btn-default btn-sm btn-flat'])?>
                <?= Html::submitButton(($link->id ? 'Изменить' : 'Добавить'), [
                    'class' => 'btn btn-primary btn-flat btn-sm',
                    'name' => 'signup-button',
                ])?>
                <?php if ($link->id) {
                    Modal::begin([
                        'header' => $link->anchor.' '.Html::a('<i class="fa fa-external-link"></i>', $link->url, ['target' => '_blank']),
                        'toggleButton' => ['label' => 'Удалить', 'class' => 'btn btn-danger btn-flat btn-sm'],
                        'footer' => Html::a('Отмена', '#', ['data-dismiss' => 'modal', 'class' => 'btn btn-default btn-flat btn-sm']) .
                            Html::a('Удалить', ['news-del', 'links_id' => $link->id], ['class' => 'btn btn-danger btn-flat btn-sm']),
                    ]);
                    echo '<p>Действительно удалить новость?</p>';
                    Modal::end();
                }?>

                <?php ActiveForm::end(); ?>

            </div>
        </div>

    </div>
</div>
