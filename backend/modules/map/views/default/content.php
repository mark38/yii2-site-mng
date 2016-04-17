<?php
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Tabs;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use backend\modules\map\MapAsset;
use backend\widgets\ckeditor\CKEditor;
use iutbay\yii2kcfinder\KCFinder;

/** @var $this \yii\web\View */
/** @var $link \common\models\main\Links */
/** @var $contents \common\models\main\Contents */

$this->title = 'Управление контентом';
$this->params['breadcrumbs'][] = ['label' => 'Управление ссылками', 'url' => ['/map/links', 'categories_id' => $link->categories_id]];
$this->params['breadcrumbs'][] = $this->title;

MapAsset::register($this);

?>

<div class="row">
    <div class="col-sm-6">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Параметры ссылки</h3> <?=Html::a('<i class="fa fa-external-link"></i>', $link->url, ['target' => '_blank'])?>
                <div class="box-tools pull-right"></div>
            </div>
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <?= Tabs::widget([
                        'items' => [
                            [
                                'label' => 'Основные параметры',
                                'content' => '<p>' .
                                    '<dl class="dl-horizontal">' .
                                    Html::tag('dt', $link->getAttributeLabel('anchor')).Html::tag('dd', $link->anchor) .
                                    Html::tag('dt', $link->getAttributeLabel('name')).Html::tag('dd', $link->name) .
                                    Html::tag('dt', $link->getAttributeLabel('title')).Html::tag('dd', $link->title) .
                                    Html::tag('dt', $link->getAttributeLabel('state')).Html::tag('dd', ($link->state == 1 ? 'Опубликована' : 'Заблокирована')) .
                                    '</dl>' .
                                    '</p>',
                                'active' => true
                            ],
                            [
                                'label' => 'Дополнительно (системные параметры)',
                                'content' => '<p>' .
                                    '<dl class="dl-horizontal">' .
                                    Html::tag('dt', $link->getAttributeLabel('categories_id')).Html::tag('dd', $link->category->comment.' ('.$link->category->name.')') .
                                    Html::tag('dt', $link->getAttributeLabel('layouts_id')).Html::tag('dd', $link->layout->comment.' ('.$link->layout->name.')') .
                                    Html::tag('dt', $link->getAttributeLabel('views_id')).Html::tag('dd', $link->view->comment.' ('.$link->view->name.')') .
                                    Html::tag('dt', $link->getAttributeLabel('url')).Html::tag('dd', Html::a($link->url, $link->url, ['target' => '_blank'])) .
                                    '</dl>' .
                                    '</p>',
                            ],
                            [
                                'label' => 'SEO',
                                'content' => '<p>' .
                                    '<dl class="dl-horizontal">' .
                                    Html::tag('dt', $link->getAttributeLabel('keywords')).Html::tag('dd', $link->keywords) .
                                    Html::tag('dt', $link->getAttributeLabel('description')).Html::tag('dd', $link->description) .
                                    Html::tag('dt', $link->getAttributeLabel('priority')).Html::tag('dd', $link->priority) .
                                    '</dl>' .
                                    '</p>',
                            ]
                        ]
                    ])?>
                </div>
            </div>
        </div>
    </div>
</div>

<div>
    <?php
    foreach ($contents as $index => $content) {
        $form = ActiveForm::begin([
            'action' => Url::to(['save-content', 'links_id' => Yii::$app->request->get('links_id'), 'categories_id' => Yii::$app->request->get('categories_id')]),
            'options' => [
                'onsubmit' => "return false;",
                'id' => 'form-content-'.$index,
                'style' => 'margin-bottom: 30px;'
            ]
        ]);
        echo Html::beginTag('div', ['class' => 'row']);

        echo Html::tag('div',
            $form->field($contents[$index], 'text')->widget(CKEditor::className(), [
                'options' => [
                    'rows' => 6,
                    'name' => 'content-'.$index,
                    'id' => 'content-'.$index
                ],
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
                //'clientOptions' => ['config.extraPlugins' => 'codeSnippet']
            ])->label(false),
            ['class' => 'col-sm-9']
        );

        echo Html::beginTag('div', ['class' => 'col-sm-3']);
        echo $form->field($contents[$index], 'css_class');
        echo $form->field($contents[$index], 'seq');
        echo Html::submitButton('Сохранить', [
            'onclick' => "content.save({$index})",
            'class' => 'btn btn-sm btn-primary btn-flat'
        ]);
        if ($index > 0) {
            echo '&nbsp;';
            Modal::begin([
                'header' => 'Блок контента #'.($index + 1),
                'toggleButton' => ['label' => 'Удалить', 'class' => 'btn btn-danger btn-flat btn-sm'],
                'footer' => Html::a('Отмена', '#', ['data-dismiss' => 'modal', 'class' => 'btn btn-default btn-flat btn-sm']) .
                    Html::a('Удалить', Url::current(['action' => 'del', 'id' => $content->id]), ['class' => 'btn btn-danger btn-flat btn-sm']),
            ]);
            echo '<p>Контент будет удалён со всем содержимым.</p><p>Действительно удалить?</p>';
            Modal::end();
        }

        echo Html::endTag('div');

        echo Html::endTag('div');
        ActiveForm::end();

        echo '<hr>';
    }

    echo Html::tag('div', Html::a('<i class="fa fa-plus"></i> Добавить блок с контентом', Url::current(['action' => 'add']), ['class' => 'btn btn-default btn-flat']), ['class' => 'text-center']);
    ?>
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