<?php
use yii\bootstrap\Html;
use kartik\form\ActiveForm;
use yii\bootstrap\Tabs;
use yii\bootstrap\Modal;
use kartik\date\DatePicker;
use kartik\daterange\DateRangePicker;
use mark38\galleryManager\GalleryManager;
use backend\widgets\ckeditor\CKEditor;
use iutbay\yii2kcfinder\KCFinder;
use common\models\news\NewsTypes;

/**
 * @var $this \yii\web\View
 * @var $news \app\modules\news\models\NewsForm
 */

/** @var $news_type NewsTypes */
/** @var $link \common\models\main\Links */
/** @var $prev_news \common\models\main\Contents */
/** @var $full_news \common\models\main\Contents */

$this->title = 'Редактирование нововсти';

$this->params['breadcrumbs'][] = ['label' => 'Все новости', 'url' => 'index'];

$linkClose = ['index'];

?>

<div class="row">
    <div class="col-md-7">

        <div class="box box-default">
            <div class="box-body">

                <?php $form=ActiveForm::begin(); ?>

                <?=$form->field($news, 'news_types_id')->dropDownList($news->newsTypes)?>

                <?php ActiveForm::end(); ?>

            </div>
        </div>

    </div>
</div>
