<?php
use yii\bootstrap\Html;

/**
 * @var $this \yii\web\View
 * @var $shopClientCart \common\models\shop\ShopClientCarts
 * @var $num
 */

$items = $shopClientCart->shopCart->shopCartItems;
$goods = $shopClientCart->shopCart->shopCartGoods;
$amount = 0;
$price = 0;
if ($items) {
    /** @var \common\models\shop\ShopCartItems $item */
    foreach ($items as $item) {
        $amount += $item->amount;
        $price += $item->amount * $item->price;
    }
}
if ($goods) {
    /** @var \common\models\shop\ShopCartGoods $good */
    foreach ($goods as $good) {
        $amount += $good->amount;
        $price += $good->amount * $good->price;
    }
}

?>

<tr>
    <td><?=$num?></td>
    <td><?=date('d.m.Y H:i', $shopClientCart->shopCart->checkout_at)?></td>
    <td><?=$shopClientCart->shopClient->city ? $shopClientCart->shopClient->city : Html::tag('small', 'Не указан', ['class' => 'text-muted'])?></td>
    <td><?=$shopClientCart->shopClient->street ? $shopClientCart->shopClient->street : Html::tag('small', 'Не указан', ['class' => 'text-muted'])?></td>
    <td><?=$shopClientCart->shopClient->home_number ? $shopClientCart->shopClient->home_number : Html::tag('small', 'Не указан', ['class' => 'text-muted'])?></td>
    <td><?=$shopClientCart->shopClient->flat_number ? $shopClientCart->shopClient->flat_number : Html::tag('small', 'Не указан', ['class' => 'text-muted'])?></td>
    <td><?=$shopClientCart->shopClient->fio ? $shopClientCart->shopClient->fio : Html::tag('small', 'Не указан', ['class' => 'text-muted'])?></td>
    <td><?=$shopClientCart->shopClient->phone ? $shopClientCart->shopClient->phone : Html::tag('small', 'Не указан', ['class' => 'text-muted'])?></td>
    <td><?=$shopClientCart->shopClient->email ? $shopClientCart->shopClient->email : Html::tag('small', 'Не указан', ['class' => 'text-muted'])?></td>
    <td><?=$shopClientCart->shopClient->comment ? Html::tag('small', $shopClientCart->shopClient->comment, ['class' => 'text-muted']) : Html::tag('small', 'Не указан', ['class' => 'text-muted'])?></td>
    <td><?=$amount?></td>
    <td><?=preg_replace('/\,00/', '', number_format($price, 2, ',', '&thinsp;'))?></td>
</tr>
