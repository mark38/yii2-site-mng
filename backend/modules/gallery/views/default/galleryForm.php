<?php
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use mark38\galleryManager\GalleryManager;

/** @var $this \yii\web\View */
/** @var $gallery_group \common\models\gallery\GalleryGroups */

//$link_close = Url::to([]);
$link_close = '';
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
            <h3 class="box-title"><?=Yii::$app->request->get('action') == 'add' ? 'Новая фотогалерея' : 'Редактирование галареи ('.$gallery_group->name.')'?></h3>
            <div class="box-tools pull-right">
                <?=Html::a('<i class="fa fa-times"></i>', $link_close, ['class' => 'btn btn-box-tool'])?>
            </div>
        </div>
        <div class="box-body">

            <?= $form->field($gallery_group, 'name')?>

            <div class="form-group">
                <div class="col-sm-5 col-sm-offset-4">
                    <?= Html::a('Отменить', [], [
                        'class' => 'btn btn-default btn-flat btn-sm',
                    ])?>
                    <?= Html::submitButton(Yii::$app->request->get('action') == 'add' ? 'Добавить' : 'Изменить', [
                        'class' => 'btn btn-primary btn-flat btn-sm',
                        /*'name' => 'signup-button'*/
                    ])?>

                    <?php if (Yii::$app->request->get('action') == 'ch') {
                        Modal::begin([
                            'header' => $gallery_group->name,
                            'toggleButton' => ['label' => 'Удалить', 'class' => 'btn btn-danger btn-flat btn-sm'],
                            'footer' => Html::a('Отмена', '#', ['data-dismiss' => 'modal', 'class' => 'btn btn-default btn-flat btn-sm']) .
                                Html::a('Удалить', ['gallery-del', 'gallery_groups_id' => $gallery_group->id], ['class' => 'btn btn-danger btn-flat btn-sm']),
                        ]);
                        echo '<p>Действительно удалить галерею?</p>';
                        Modal::end();
                    }?>
                </div>
            </div>

        </div>
        <?php if (Yii::$app->request->get('action') == 'ch') {?>
            <div class="box-footer">
                <?= $form->field($gallery_group, 'id', [
                    'template' => '<div class="col-sm-12">{input}</div>'
                ])->widget(GalleryManager::className(), [
                    'gallery_groups_id' => $gallery_group->id,
                    'pluginOptions' => [
                        'type' => $gallery_group->galleryType->name,
                        'apiUrl' => 'gallery-manager',
                        'webRoute' => Yii::getAlias('@frontend/web'),
                    ]
                ])->label(false);?>
            </div>
        <?php }?>
    </div>
<?php ActiveForm::end()?>