<?php
use common\models\shop\ShopPriceTypes;

/**
 * @var \backend\modules\shop\models\LinkGoodForm $link
 * @var \backend\modules\shop\models\GoodForm @good
 * @var \kartik\form\ActiveFormAsset $form
 */

$priceTypes = ShopPriceTypes::find()->orderBy(['def' => SORT_DESC, 'name' => SORT_ASC])->all();

/** @var ShopPriceTypes $priceType */
foreach ($priceTypes as $priceType) {
    echo $form->field($good, 'priceValues['.$priceType->id.']')->label($priceType->name);
}
?>

