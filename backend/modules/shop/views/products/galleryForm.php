<?php
use yii\bootstrap\ActiveForm;
use mark38\galleryManager\GalleryManager;

/** @var $this \yii\web\View */
/** @var $galleryGroup \common\models\gallery\GalleryGroups */


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

echo  $form->field($galleryGroup, 'id', [
    'template' => '<div class="col-sm-12">{input}</div>'
])->widget(GalleryManager::className(), [
    'gallery_groups_id' => $galleryGroup->id,
    'pluginOptions' => [
        'type' => $galleryGroup->galleryType->name,
        'apiUrl' => 'gallery-manager',
        'webRoute' => Yii::getAlias('@frontend/web'),
    ]
])->label(false);

ActiveForm::end();

?>