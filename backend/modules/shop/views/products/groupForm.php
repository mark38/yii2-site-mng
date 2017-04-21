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
 * @var $group \common\models\shop\ShopGroups
 */

$linkClose = ['/shop/products/links'];

?>

<div class="box box-default">
    <div class="box-header with-border">
        <h3>
            <?php switch ($action) {
                case "add": echo 'Новая группа'; break;
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
                            Html::endTag('p'),
                    ],
                    [
                        'label' => 'Медиа',
                        'content' => Html::beginTag('p') .
                            Html::endTag('p'),
                    ],
                    [
                        'label' => 'Свойства',
                        'content' => Html::beginTag('p') .
                            $form->field($group, 'groupProperties')->checkboxList(ArrayHelper::map(ShopProperties::find()->orderBy(['seq' => SORT_ASC])->all(), 'id', 'anchor')) .
                            Html::endTag('p'),
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
                    Html::a('Удалить', ['/map/link-del', 'categories_id' => Yii::$app->request->get('categories_id'), 'links_id' => $link->id], ['class' => 'btn btn-danger btn-flat btn-sm']),
            ]);
            echo '<p>Ссылка будет удалена со всем содержимым, в том числе контент страницы, а также дочерние ссылки, если такие имеются.</p><p>Действительно удалить ссылку?</p>';
            Modal::end();
        }?>

        <?php ActiveForm::end()?>
    </div>
</div>
