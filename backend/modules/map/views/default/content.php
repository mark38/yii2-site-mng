<?php
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Tabs;
use backend\modules\map\MapAsset;
use backend\widgets\ckeditor\CKEditor;
use iutbay\yii2kcfinder\KCFinder;

/** @var $this \yii\web\View */
/** @var $link \common\models\main\Links */
/** @var $contents \common\models\main\Contents */

$this->title = 'Управление контентом';
$this->params['breadcrumbs'][] = ['label' => 'Управление ссылками', 'url' => ['/map/links', 'categories_id' => Yii::$app->request->get('categories_id')]];
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

<div>
    <?php
    foreach ($contents as $index => $content) {
        $form = ActiveForm::begin([
            'options' => [
                'style' => 'margin-bottom: 30px;'
            ]
        ]);
        echo $form->field($contents[$index], 'text')->widget(CKEditor::className(), [
            'options' => [
                'rows' => 6,
                'name' => 'content-'.$index,
                'id' => 'content-'.$index
            ],
            'preset' => 'full',
            'clientOptions' => ['config.extraPlugins' => 'codeSnippet']
        ])->label(false);

        echo Html::submitButton('Сохранить', [
            'class' => 'btn btn-sm btn-primary btn-flat'
        ]);

        ActiveForm::end();
    }
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