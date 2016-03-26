<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

$left_view = false;
if (isset($this->context->module->id)) {
    if (file_exists(Yii::getAlias('@app/modules/').$this->context->module->id.'/views/layouts/left.php')) $left_view = '../../modules/'.$this->context->module->id.'/views/layouts/left.php';
}
if (!$left_view) $left_view = 'left.php';


if (Yii::$app->controller->action->id === 'login') { 
/**
 * Do not use this code in your template. Remove it. 
 * Instead, use the code  $this->layout = '//main-login'; in your controller.
 */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }

    dmstr\web\AdminLteAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    ?>

    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="hold-transition fixed skin-blue sidebar-mini">
    <?php $this->beginBody() ?>

    <div class="wrapper">
        <div class="alert" id="alert-flash">
            <button type="button" class="close" aria-hidden="true">×</button>
            <h4></h4>
            <p></p>
        </div>

        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <?= $this->render(
            $left_view,
            ['directoryAsset' => $directoryAsset]
        )
        ?>

        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>

    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
