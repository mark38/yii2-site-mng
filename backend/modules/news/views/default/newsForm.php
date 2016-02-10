<?php
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Tabs;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use common\models\news\NewsTypes;
use kartik\date\DatePicker;
use backend\widgets\gallery\GalleryManager;
use backend\widgets\ckeditor\CKEditor;
use iutbay\yii2kcfinder\KCFinder;
use common\models\main\Layouts;
use common\models\main\Views;

/** @var $this \yii\web\View */
/** @var $news_type NewsTypes */
/** @var $news \common\models\news\News */
/** @var $link \common\models\main\Links */
/** @var $prev_news \common\models\main\Contents */
/** @var $full_news \common\models\main\Contents */

$link_close = [''];
?>

<?php $form = ActiveForm::begin([
    'method' => 'post',
    'layout' => 'horizontal',
    'fieldConfig' => [
        //'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
        'horizontalCssClasses' => [
            'label' => 'col-sm-4',
            'offset' => 'col-sm-offset-4',
            'wrapper' => 'col-sm-5',
            'error' => '',
            'hint' => '',
        ],
    ],
]); ?>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title"><?=Yii::$app->request->get('news') == 'add' ? 'Новая новость ('.$news_type->name.')' : 'Редактирование новости ('.$news_type->name.')'?></h3>
        <div class="box-tools pull-right">
            <?=Html::a('<i class="fa fa-times"></i>', $link_close, ['class' => 'btn btn-box-tool'])?>
        </div>
    </div>
    <div class="box-body">

        <?= Tabs::widget([
            'items' => [
                [
                    'label' => 'Основные параметры',
                    'content' => '<p>' .
                        $form->field($link, 'state')->checkbox() .
                        $form->field($news, 'news_types_id')->dropDownList(ArrayHelper::map(NewsTypes::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name')) .
                        $form->field($link, 'anchor')->label('Заголовок новости') .
                        $form->field($link, 'title')->label('Заголовок страницы (опционально)') .
                        $form->field($news, 'date')->widget(DatePicker::className(), [
                            'options' => [
                                'placeholder' => '___.___.______',
                            ],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'dd.mm.yyyy'
                            ]
                        ]) .
                        $form->field($link, 'gallery_images_id')->widget(GalleryManager::className(), [
                            'group' => false,
                            'gallery_groups_id' => 2,
                            'pluginOptions' => [
                                'type' => 'news',
                                'apiUrl' => 'gallery-manager',
                                'webRoute' => Yii::getAlias('@frontend/web'),
                            ]
                        ])->label('Предварительное фото') .
                        '</p>',
                    'active' => true
                ],
                [
                    'label' => 'Дополнительно (системные параметры)',
                    'content' => '<p>' .
                        $form->field($link, 'layouts_id')->dropDownList(ArrayHelper::map(Layouts::find()->orderBy(['seq' => SORT_ASC])->all(), 'id', 'comment')) .
                        $form->field($link, 'views_id')->dropDownList(ArrayHelper::map(Views::find()->orderBy(['seq' => SORT_ASC])->all(), 'id', 'comment')) .
                        $form->field($link, 'url')->staticControl() .
                        $form->field($link, 'name') .
                        '</p>',
                ],
                [
                    'label' => 'SEO',
                    'content' => '<p>' .
                        $form->field($link, 'keywords')->textarea() .
                        $form->field($link, 'description')->textarea() .
                        $form->field($link, 'priority') .
                        '</p>',
                ]
            ],
        ])?>

    </div>
    <div class="box-footer">

        <?=$form->field($news, 'prev_text', [
            'template' => '{label}<div class="col-sm-12">{input}</div><div class="col-sm-10">{error}</div>',
            'labelOptions' => ['class' => 'col-sm-12'],
        ])->textarea(['maxlength' => true, 'rows' => 2, 'id' => 'prev-text'])?>

        <?=$form->field($news, 'full_text', [
            'template' => '{label}<div class="col-sm-12">{input}</div><div class="col-sm-10">{error}</div>',
            'labelOptions' => ['class' => 'col-sm-12']
        ])->widget(CKEditor::className(), [
            'options' => ['id' => 'full-text'],
            'preset' => 'custom',
            'clientOptions' => [
                'height' => 300,
                'toolbarGroups' => [
                    ['name' => 'document', 'groups' => ['mode', 'document', 'doctools']],
                    ['name' => 'clipboard', 'groups' => ['clipboard', 'undo' ]],
                    ['name' => 'editing', 'groups' => ['find', 'selection', 'spellchecker' ]],
                    ['name' => 'insert'],
                    ['name' => 'basicstyles', 'groups' => ['basicstyles', 'cleanup' ]],
                    ['name' => 'paragraph', 'groups' => ['list', 'indent', 'blocks', 'align', 'bidi' ]],
                    ['name' => 'styles'],
                    ['name' => 'colors'],
                    ['name' => 'tools'],
                    ['name' => 'others'],
                    ['name' => 'links']
                ],
            ],
        ])?>

        <?= Html::a('Отмена', $link_close, ['class' => 'btn btn-default btn-sm btn-flat'])?>
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
                    Html::a('Удалить', ['news-del', 'links_id' => $link->id], ['class' => 'btn btn-danger btn-flat btn-sm']),
            ]);
            echo '<p>Действительно удалить новость?</p>';
            Modal::end();
        }?>
    </div>
</div>

<?php ActiveForm::end()?>

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
