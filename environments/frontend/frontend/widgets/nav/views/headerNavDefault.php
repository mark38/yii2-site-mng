<?php
/**
 * @var \yii\web\View $this
 * @var \common\models\main\Links $link
 * @var \common\models\main\Links $childLinks
 */
use kartik\helpers\Html;

?>

<div class="content">
    <div class="catalog-links row cnt-space">
        <?php
        /** @var \common\models\main\Links $link */
        foreach ($childLinks as $childLink) {
            $anchor = preg_replace('/\(/', '<small>(', $childLink->anchor);
            $anchor = preg_replace('/\)/', ')</small>', $anchor);
            echo Html::tag('div', Html::a($anchor, $childLink->url, ['class' => 'group-link']), ['class' => 'col-lg-4 col-md-6 col-sm-12']);
        }
        ?>
    </div>

    <div class="catalog-footer text-center cnt-space hidden-xs">
        <?= Html::a('Весь каталог', $link->url, ['class' => 'btn btn-default']) ?>
    </div>
</div>