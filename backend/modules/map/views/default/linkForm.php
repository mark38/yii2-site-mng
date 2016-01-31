<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Tabs;
use yii\helpers\ArrayHelper;
use common\models\main\Categories;
use common\models\main\Layouts;
use common\models\main\Views;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var $link \common\models\main\Links */

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
        <h3 class="box-title"><?=Yii::$app->request->get('mng_link') == 'add' ? 'Добавлекние новой ссылки' : 'Редактирование ссылки'?></h3>
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>
    <div class="box-body">

        <?= Tabs::widget([
            'items' => [
                [
                    'label' => 'Основные параметры',
                    'content' => '<p>' .
                        $form->field($link, 'anchor') .
                        $form->field($link, 'link_name')->hint('(не обязательно)') .
                        $form->field($link, 'title') .
                        $form->field($link, 'state')->checkbox() .
                        '</p>',
                    'active' => true
                ],
                [
                    'label' => 'Дополнительно (системные параметры)',
                    'content' => '<p>' .
                        $form->field($link, 'categories_id')->dropDownList(ArrayHelper::map(Categories::find()->orderBy(['seq' => SORT_ASC])->all(), 'id', 'comment')) .
                        $form->field($link, 'layouts_id')->dropDownList(ArrayHelper::map(Layouts::find()->orderBy(['comment' => SORT_ASC])->all(), 'id', 'comment')) .
                        $form->field($link, 'views_id')->dropDownList(ArrayHelper::map(Views::find()->orderBy(['comment' => SORT_ASC])->all(), 'id', 'comment')) .
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
    <div class="box-footer">
        <?= Html::submitButton(($link->id ? 'Изменить' : 'Добавить'), [
            'class' => 'btn btn-primary btn-flat btn-sm',
            'name' => 'signup-button',
            'value' => 'Добавить',
        ]) ?>&nbsp;
        <?php if ($link->id) {
            echo Html::a('Ретактор контента', Url::to(['/map/content', 'categories_id' => Yii::$app->request->get('categories_id'), 'links_id' => $link->id]), [
                'class' => 'btn btn-info btn-flat btn-sm'
            ]);
        }?>
    </div>
</div>

<?php ActiveForm::end()?>

