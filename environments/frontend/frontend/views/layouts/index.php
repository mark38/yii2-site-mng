<?php
use yii\helpers\Html;
use frontend\assets\AppAsset;
use frontend\widgets\content\RenderView;

/* @var $this \yii\web\View */
/* @var $content string */

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

        <section class="content">
            <?php
            echo Html::beginTag('div', ['class' => 'container']);

            if ($link->h1) echo Html::tag('h1', $link->h1, ['class' => 'h1']);

            if ($content) {
                echo '<div class="row">' .
                        '<div class="col-md-12">'.Html::tag('div', $content, ['class' => 'bg-w cnt-space']).'</div>' .
                     '</div>';
            }

            echo Html::endTag('div');

            ?>
        </section>
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
