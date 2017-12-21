<?php
use yii\helpers\Html;
use frontend\assets\AppAsset;
use frontend\widgets\content\RenderView;
use frontend\widgets\nav\Left;
use yii\widgets\Breadcrumbs;

/**
 * @var \yii\web\View $this
 * @var string $content
 */

AppAsset::register($this);

$link = Yii::$app->view->params['links']['activeLink'];
$breadcrumbs = Yii::$app->view->params['links']['breadcrumbs'];
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="yandex-verification" content="651274afe678deff" />
    <meta name="google-site-verification" content="C8tIdgz1FOk6FZKDdK9HwNzJMGWg9P7h_yPzpRB3WXQ" />
    <meta name="msvalidate.01" content="" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
</head>
<body>
    <?php $this->beginBody() ?>

    <div class="wrap">
        <?=RenderView::widget(['name' => 'header'])?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                'homeLink' => false,
                'options' => [
                    'class' => 'breadcrumb'
                ]
            ])?>
            <?=$content?>
        </div>
    </div>

    <footer class="footer">
        <?= RenderView::widget(['name' => 'footer']); ?>
    </footer>

    <?= RenderView::widget(['name' => 'analytics']); ?>
    <?= uran1980\yii\widgets\scrollToTop\ScrollToTop::widget(); ?>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
