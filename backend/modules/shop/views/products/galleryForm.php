<?php
/**
 * @var \yii\web\View $this
 * @var \common\models\gallery\GalleryGroups $galleryGroup
 */
use yii\bootstrap\ActiveForm;
//use mark38\galleryManager\GalleryManager;
use backend\widgets\gallery\GalleryManager;
use kartik\helpers\Html;

$form = ActiveForm::begin([
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
]);

if ($galleryGroup) {
    echo Html::beginTag('div', ['class' => 'lext-left']);
    echo $form->field($galleryGroup, 'id')->widget(GalleryManager::className(), [
        'gallery_groups_id' => $galleryGroup->id,
        'pluginOptions' => [
            'type' => $galleryGroup->galleryType->name,
            'apiUrl' => 'gallery-manager',
            'webRoute' => Yii::getAlias('@frontend/web'),
        ]
    ])->label(false);
    echo Html::endTag('div');
}

ActiveForm::end();

?>