<?php
/**
 * @var $this \yii\web\View
 * @var $linksTop \common\models\main\Links
 * @var $linksHeader \common\models\main\Links
 */
use kartik\helpers\Html;

?>

<div class="row">
    <div class="col-sm-6">
        <ul class="list-unstyled">
            <?php
            echo Html::tag('li', Html::a('Главная', Yii::$app->homeUrl));
            /** @var \common\models\main\Links $linksTop */
            foreach ($linksTop as $link) {
                echo Html::tag('li', Html::a($link->anchor, $link->url));
            }
            echo Html::tag('li', Html::a('Политика конфиденциальности', ['/politika-konfidencialnosti']));
            ?>
        </ul>
    </div>

    <div class="col-sm-6">
        <ul class="list-unstyled">
            <?php
            echo Html::tag('li', 'Каталог:');
            /** @var \common\models\main\Links $linksTop */
            foreach ($linksHeader as $link) {
                echo Html::tag('li', Html::a($link->anchor, $link->url));
            }
            ?>
        </ul>
    </div>
</div>


