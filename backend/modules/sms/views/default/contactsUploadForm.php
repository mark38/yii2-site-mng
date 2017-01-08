<?php
use kartik\form\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap\Html;

/** @var $this \yii\web\View */
/** @var $upload \app\modules\sms\models\UploadFileForm */

?>

<div>
    <?php $form = ActiveForm::begin([
        'id' => 'form-contacts-upload',
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => [
            'labelSpan' => 4
        ],
        'options' => [
            'data-url' => Url::to(['contacts-upload']),
            'enctype' => 'multipart/form-data'
        ],
    ])?>

    <?=$form->field($upload, 'uploadFile')->fileInput()?>

    <?php
    echo Html::submitButton('Отправить');
    if (!Yii::$app->request->isAjax) {
        echo Html::submitButton('Отправить');
    }
    ?>

    <?php ActiveForm::end(); ?>
</div>
