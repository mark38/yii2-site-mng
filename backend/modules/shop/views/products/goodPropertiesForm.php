<?php
use common\models\shop\ShopGroupProperties;

/**
 * @var \backend\modules\shop\models\LinkGoodForm $link
 * @var \backend\modules\shop\models\GoodForm @good
 * @var \kartik\form\ActiveFormAsset $form
 */

$groupProperties = ShopGroupProperties::find()->joinWith('shopGroup')->where(['links_id' => $link->parent])->all();

/** @var ShopGroupProperties $groupProperty */
foreach ($groupProperties as $i => $groupProperty) {
    $addon = $groupProperty->shopProperty->unit ? ['addon' => ['append' => ['content' => $groupProperty->shopProperty->unit]]] : '';
    echo $form->field($good, 'propertyValues['.$groupProperty->shop_properties_id.']', [
        'addon' => ($groupProperty->shopProperty->unit ? ['append' => ['content' => $groupProperty->shopProperty->unit]] : '')
    ])->label($groupProperty->shopProperty->name);
}
?>

