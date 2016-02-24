<?php
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use mark38\galleryManager\GalleryManager;

/** @var $this \yii\web\View */
/** @var $gallery_group \common\models\gallery\GalleryGroups */

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



    </div>
</div>

<?php ActiveForm::end()?>