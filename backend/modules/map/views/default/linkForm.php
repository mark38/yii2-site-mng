<?php
use kartik\form\ActiveForm;
use yii\bootstrap\Tabs;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\main\Layouts;
use common\models\main\Views;

/**
 * @var $this \yii\web\View
 * @var $link \common\models\main\Links
 * @var $galleryImage \common\models\gallery\GalleryImagesForm
 */

$link_close = ['/map/links', 'categories_id' => Yii::$app->request->get('categories_id')];
$layouts = array();
foreach (Layouts::find()->orderBy(['seq' => SORT_ASC])->all() as $layout) {
    $layouts[$layout['id']] = $layout->comment.' &mdash; '.$layout->name;
}
$views = array();
foreach (Views::find()->orderBy(['seq' => SORT_ASC])->all() as $view) $views[$view->id] = $view->comment . ' &mdash; '.$view->name;

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

<?php $form = ActiveForm::begin(); ?>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title"><?=Yii::$app->request->get('action') == 'add' ? 'Добавление новой ссылки' : 'Редактирование ссылки'?></h3>
        <div class="box-tools pull-right">
            <?=Html::a('<i class="fa fa-times"></i>', $link_close, ['class' => 'btn btn-box-tool'])?>
        </div>
    </div>
    <div class="box-body">
        <div class="nav-tabs-custom">
            <?= Tabs::widget([
                'items' => [
                    [
                        'label' => 'Основные параметры',
                        'content' => Html::beginTag('p') .
                            $form->field($link, 'state')->checkbox() .
                            $form->field($link, 'start')->checkbox() .
                            $form->field($link, 'anchor') .
                            $form->field($link, 'name')->hint('(не обязательно)') .
                            $form->field($link, 'title') .
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
                        'label' => 'Дополнительно (системные параметры)',
                        'content' => '<p>' .
                            $form->field($link, 'layouts_id')->dropDownList($layouts, ['encode' => false]) .
                            $form->field($link, 'views_id')->dropDownList($views, ['encode' => false]) .
//                            $form->field($link, 'url')->staticControl() .
                            $form->field($link, 'url') .
                            $form->field($link, 'css_class') .
                            $form->field($link, 'icon') .
                            $form->field($link, 'seq') .
                            '</p>',
                    ],
                    [
                        'label' => 'SEO',
                        'content' => '<p>' .
                            $form->field($link, 'h1') .
                            $form->field($link, 'keywords')->textarea() .
                            $form->field($link, 'description')->textarea() .
                            $form->field($link, 'priority') .
                            '</p>',
                    ]
                ]
            ])?>
        </div>

        <?= Html::a('Отмена', $link_close, ['class' => 'btn btn-default btn-sm btn-flat'])?>

        <?= Html::submitButton(($link->id ? 'Изменить' : 'Добавить'), [
                'class' => 'btn btn-primary btn-flat btn-sm',
                'name' => 'signup-button',
                'value' => 'Добавить',
        ])?>

        <?php if ($link->id) {
            echo Html::a('Ретактор контента', Url::to(['/map/content', 'links_id' => $link->id]), [
                'class' => 'btn btn-info btn-flat btn-sm'
            ]).'&nbsp;';
            Modal::begin([
                'header' => $link->anchor.' '.Html::a('<i class="fa fa-external-link"></i>', $link->url, ['target' => '_blank']),
                'toggleButton' => ['label' => 'Удалить', 'class' => 'btn btn-danger btn-flat btn-sm'],
                'footer' => Html::a('Отмена', '#', ['data-dismiss' => 'modal', 'class' => 'btn btn-default btn-flat btn-sm']) .
                            Html::a('Удалить', ['/map/link-del', 'categories_id' => Yii::$app->request->get('categories_id'), 'links_id' => $link->id], ['class' => 'btn btn-danger btn-flat btn-sm']),
            ]);
            echo '<p>Ссылка будет удалена со всем содержимым, в том числе контент страницы, а также дочерние ссылки, если такие имеются.</p><p>Действительно удалить ссылку?</p>';
            Modal::end();
        }?>
    </div>
</div>

<?php ActiveForm::end()?>

