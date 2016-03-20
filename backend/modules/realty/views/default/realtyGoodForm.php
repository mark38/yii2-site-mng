<?php
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\bootstrap\Tabs;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use mark38\galleryManager\GalleryManager;
use common\models\realty\RealtyGroups;
use backend\widgets\ckeditor\CKEditor;
use iutbay\yii2kcfinder\KCFinder;

/** @var $realty_good \common\models\realty\RealtyGoods */
/** @var $realty_groups \common\models\realty\RealtyGroups */

$link_close = [''];

?>

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

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title"><?=Yii::$app->request->get('action') == 'add' ? 'Новое объявление' : 'Редактирование объявления ('.$realty_good->realtyGroup->name.')'?></h3>
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
                        $form->field($realty_good, 'realty_groups_id')->dropDownList(ArrayHelper::map($realty_groups, 'id', 'name'), ['disabled' => 'disabled']) .
                        $form->field($realty_good, 'hot')->checkbox() .
                        $form->field($realty_good, 'name') .
                        $form->field($realty_good, 'address') .
                        $form->field($realty_good, 'price') .
                        $form->field($realty_good, 'square') .
                        $form->field($realty_good, 'coords') .
                        $form->field($realty_good, 'gallery_groups_id', [
                            'template' => '{label}<div class="col-sm-8">{input}</div><div class="col-sm-8">{error}</div>',
                            'labelOptions' => ['class' => 'col-sm-4 control-label'],
                        ])->widget(GalleryManager::className(), [
                            'pluginOptions' => [
                                'type' => 'realty',
                                'apiUrl' => 'gallery-manager',
                                'webRoute' => Yii::getAlias('@frontend/web'),
                            ],
                            //'options' => ['id' => 'gallery-upload'],
                        ]) .
                        '</p>',
                    'active' => true
                ],
                [
                    'label' => 'Дополнительно (системные параметры)',
                    'content' => '<p></p>',
                ]
            ]
        ])?>

    </div>

    <div class="box-footer">
        <?=$form->field($realty_good, 'text', [
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

        <?= Html::submitButton(($realty_good->id ? 'Изменить' : 'Добавить'), [
            'class' => 'btn btn-primary btn-flat btn-sm',
            'name' => 'signup-button',
            'value' => 'Добавить',
        ])?>

        <?php if ($realty_good->id) {
            echo Html::a('Ретактор контента', Url::to(['/map/content', 'categories_id' => Yii::$app->request->get('categories_id'), 'links_id' => $realty_good->id]), [
                    'class' => 'btn btn-info btn-flat btn-sm'
                ]).'&nbsp;';
            Modal::begin([
                'header' => $realty_good->realtyGroup->name,
                'toggleButton' => ['label' => 'Удалить', 'class' => 'btn btn-danger btn-flat btn-sm'],
                'footer' => Html::a('Отмена', '#', ['data-dismiss' => 'modal', 'class' => 'btn btn-default btn-flat btn-sm']) .
                    Html::a('Удалить', ['', 'realty_goods_id' => Yii::$app->request->get('realty_goods_id'), 'id' => $realty_good->id], ['class' => 'btn btn-danger btn-flat btn-sm']),
            ]);
            echo '<p>Действительно удалить объявление?</p>';
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
