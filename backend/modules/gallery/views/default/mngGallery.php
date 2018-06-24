<?php
/**
 * @var \yii\web\View $this
 * @var \common\models\gallery\GalleryTypes $galleryType
 * @var \common\models\gallery\GalleryGroups $galleryGroup
 */

use kartik\form\ActiveForm;
use kartik\helpers\Html;
use yii\bootstrap\Modal;
use backend\widgets\gallery\GalleryManager;

$this->title = 'Управление изображениями';
$this->params['breadcrumbs'] = [
    ['url' => 'index', 'label' => 'Все группы'],
];
if ($galleryType) $this->params['breadcrumbs'][] = ['url' => ['index', 'gallery_types_id' => $galleryType->id], 'label' => $galleryType->comment];

?>
<div class="box box-solid">
    <div class="box-header with-border"><h3 class="box-title">Параметры для &laquo;<?= $galleryType->comment ?>&raquo;</h3></div>
    <div class="box-body">
        <ul class="list-unstyled">
            <li><small class="text-muted">Каталог загрузки:</small> <?= $galleryType->destination ?></li>
            <li><small class="text-muted">Разрешение малельного изображения:</small> <?= $galleryType->small_width ?>x<?= $galleryType->small_height ?>px</li>
            <li><small class="text-muted">Разрешение большого изображения:</small> <?= $galleryType->large_width ?>x<?= $galleryType->large_height ?>px</li>
            <li><small class="text-muted">Качество сжатия при смене разрешения:</small> <?= $galleryType->quality ?></li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 col-sm-12">
        <div class="box box-success">
            <?php $form = ActiveForm::begin([
                'type' => ActiveForm::TYPE_HORIZONTAL,
                'formConfig' => ['labelSpan' => 4, /*'deviceSize' => ActiveForm::SIZE_SMALL*/]
            ]); ?>

            <div class="box-header with-border">
                <h3><?= (isset($galleryGroup->name) ? 'Редактирование &laquo;'.$galleryGroup->name.'&raquo;' ?: '&mdash;'.'&raquo;' : 'Новая группа') ?></h3>
            </div>

            <div class="box-body">
                <?= $form->field($galleryGroup, 'name') ?>
            </div>

            <div class="box-footer">
                <?php
                echo Html::submitButton(isset($galleryGroup->id) ? 'Изменить' : 'Добавить', [
                    'class' => 'btn btn-primary btn-flat btn-sm',
                ]);
                echo '&nbsp;';
                if ($galleryGroup->id) {
                    Modal::begin([
                        'header' => $galleryGroup->name,
                        'toggleButton' => ['label' => 'Удалить', 'class' => 'btn btn-danger btn-flat btn-sm'],
                        'footer' => Html::a('Отмена', '#', ['data-dismiss' => 'modal', 'class' => 'btn btn-default btn-flat btn-sm']) .
                            Html::a('Удалить', ['group-del', 'gallery_groups_id' => $galleryGroup->id], ['class' => 'btn btn-danger btn-flat btn-sm']),
                    ]);
                    echo '<p>Действительно удалить галерею?</p>';
                    Modal::end();
                }
                ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <?php if (isset($galleryGroup->id)): ?>
        <div class="col-lg-6 col-sm-12">
            <div class="box box-default">
                <div class="box-body">
                    <p>Для вставки галереи необходимо в форму редактирования контента вставить следующий код:</p>
                    <code>[[gallery/photo(galleryGroupsId:<?= $galleryGroup->id ?>)]]</code>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php if (isset($galleryGroup->id)): ?>
    <div class="box box-info">
        <div class="box-body">
            <?php
            $form = ActiveForm::begin([
                'formConfig' => ['labelSpan' => 12]
            ]);

            echo $form->field($galleryGroup, 'id')->widget(GalleryManager::className(), [
                'gallery_groups_id' => $galleryGroup->id,
                'pluginOptions' => [
                    'type' => $galleryGroup->galleryType->name,
                    'apiUrl' => 'gallery-manager',
                    'webRoute' => Yii::getAlias('@frontend/web'),
                ]
            ])->label(false);

            ActiveForm::end();
            ?>
        </div>
    </div>
<?php endif; ?>