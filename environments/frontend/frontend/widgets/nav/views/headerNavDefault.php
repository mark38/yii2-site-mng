<?php
/**
 * @var \yii\web\View $this
 * @var \common\models\main\Links $link
 * @var \common\models\main\Links $childLinks
 */
use kartik\helpers\Html;

?>

<div class="content">
    <ul class="links-list">
        <?php
        /** @var \common\models\main\Links $link */
        foreach ($childLinks as $childLink) {
            $anchor = preg_replace('/\(/', '<small>(', $childLink->anchor);
            $anchor = preg_replace('/\)/', ')</small>', $anchor);
            echo Html::tag('li', Html::a($anchor, $childLink->url));
        }
        ?>
    </ul>
    <?= Html::a('Весь каталог', $link->url, ['class' => 'btn', 'style' => 'padding:0']) ?>
</div>