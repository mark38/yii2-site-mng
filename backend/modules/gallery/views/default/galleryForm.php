<?php
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use mark38\galleryManager\GalleryManager;

/** @var $this \yii\web\View */
/** @var $gallery_group \common\models\gallery\GalleryGroups */

/*$link_close = Url::to([]);*/
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


<?php ActiveForm::end()?>