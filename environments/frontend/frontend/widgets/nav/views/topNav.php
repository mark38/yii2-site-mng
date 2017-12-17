<?php
/**
 * @var $this \yii\web\View
 * @var $links \common\models\main\Links
 * @var array $breadcrumbsId
 */
use kartik\helpers\Html;

?>

<div class="top-nav-wrap hidden-xs">
    <div class="row">
        <div class="col-sm-12">
            <ul class="list-inline pull-right top-nav">
                <?php
                /** @var \backend\widgets\map\Links $link */
                foreach ($links as $link) {
                    $class = isset($breadcrumbsId[$link->id]) ? 'active' : '';

                    echo Html::beginTag('li', ['class' => $class]);
                    echo Html::a($link->anchor, $link->url);
                    echo Html::endTag('li');
                }
                ?>
            </ul>
        </div>
    </div>
</div>

