<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Tabs;
use yii\helpers\ArrayHelper;
use common\models\main\Layouts;
use common\models\main\Views;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use mark38\galleryManager\GalleryManager;

/** @var $link \common\models\main\Links */

$link_close = ['/map/links', 'categories_id' => Yii::$app->request->get('categories_id')];
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
        <h3 class="box-title"><?=Yii::$app->request->get('mng_link') == 'add' ? 'Добавление новой ссылки' : 'Редактирование ссылки'?></h3>
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
                        'content' => '<p>' .
                            $form->field($link, 'state')->checkbox() .
                            $form->field($link, 'start')->checkbox() .
                            $form->field($link, 'anchor') .
                            $form->field($link, 'name')->hint('(не обязательно)') .
                            $form->field($link, 'title') .
                            $form->field($link, 'gallery_images_id')->widget(GalleryManager::className(), [
                                'group' => false,
                                'gallery_groups_id' => 1,
                                'pluginOptions' => [
                                    'type' => 'links',
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
                        'content' => '<p>' .
                            $form->field($link, 'layouts_id')->dropDownList(ArrayHelper::map(Layouts::find()->orderBy(['seq' => SORT_ASC])->all(), 'id', 'comment')) .
                            $form->field($link, 'views_id')->dropDownList(ArrayHelper::map(Views::find()->orderBy(['seq' => SORT_ASC])->all(), 'id', 'comment')) .
                            $form->field($link, 'url')->staticControl() .
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

