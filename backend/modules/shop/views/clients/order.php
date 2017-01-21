<?php
use yii\bootstrap\Html;

/**
 * @var $this \yii\web\View
 * @var $shopClientCart \common\models\shop\ShopClientCarts
 */

$cartItems = $shopClientCart->shopCart->shopCartItems;
$cartGoods = $shopClientCart->shopCart->shopCartGoods;
$amount = 0;
$price = 0;

$products = '';

if ($cartItems) {
    /** @var \common\models\shop\ShopCartItems $cartItem */
    foreach ($cartItems as $cartItem) {
        $amount += $cartItem->amount;
        $price += $cartItem->amount * $cartItem->price;

        /** @var \common\models\shop\ShopItems $item */
        $characteristics = $cartItem->shopItemCharacteristics;
        if ($characteristics) {
            $characteristicsList = Html::beginTag('ul', ['class' => 'list-inline']);
            /** @var \common\models\shop\ShopItemCharacteristics $characteristic */
            foreach ($characteristics as $characteristic) {
                $characteristicsList .= Html::tag('li', Html::tag('small', $characteristic->shopCharacteristic->name.': ', ['class' => 'text-muted']).' '.Html::tag('small', $characteristic->name.';'));
            }
            $characteristicsList .= Html::endTag('ul');
        } else {
            $characteristicsList = '';
        }
        
        $products .= '<tr>' .
                '<td>'.Html::tag('small', $cartItem->link->anchor).'<br>'.$characteristicsList.'</td>' .
                '<td><nobr>'.$cartItem->price.' '.Html::tag('small', 'руб.', ['class' => 'text-muted']).'</nobr></td>' .
                '<td><nobr>'.$cartItem->amount.' '.Html::tag('small', 'шт.', ['class' => 'text-muted']).'</nobr></td>' .
                '<td>'.Html::a('<i class="fa fa-external-link" aria-hidden="true"></i>', $cartItem->link->url, ['target' => '_blank']).'</td>' .
            '</tr>';
    }
}
if ($cartGoods) {
    /** @var \common\models\shop\ShopCartGoods $cartGood */
    foreach ($cartGoods as $cartGood) {
        $amount += $cartGood->amount;
        $price += $cartGood->amount * $cartGood->price;

        $good = $cartGood->shopGood;

        $products .= '<tr>' .
                '<td>'.Html::tag('small', $good->link->anchor).'</td>' .
                '<td><nobr>'.$cartGood->price.' '.Html::tag('small', 'руб.', ['class' => 'text-muted']).'</nobr></td>' .
                '<td><nobr>'.$cartGood->amount.' '.Html::tag('small', 'шт.', ['class' => 'text-muted']).'</nobr></td>' .
                '<td>'.Html::a('<i class="fa fa-external-link" aria-hidden="true"></i>', $cartGood->link->url, ['target' => '_blank']).'</td>' .
            '</tr>';
    }
}

?>

<div class="row">
    <div class="col-md-5">
        <legend>Доставка</legend>
        <dl class="dl-horizontal">
            <dt>Клиент:</dt>
            <dd><?=$shopClientCart->shopClient->fio ? $shopClientCart->shopClient->fio : Html::tag('small', 'Не указан', ['class' => 'text-muted'])?></dd>
            <dt>Город:</dt>
            <dd><?=$shopClientCart->shopClient->city ? $shopClientCart->shopClient->city : Html::tag('small', 'Не указан', ['class' => 'text-muted'])?></dd>
            <dt>Улица (мкр., пер.):</dt>
            <dd><?=$shopClientCart->shopClient->street ? $shopClientCart->shopClient->street : Html::tag('small', 'Не указан', ['class' => 'text-muted'])?></dd>
            <dt>Номер дома:</dt>
            <dd><?=$shopClientCart->shopClient->home_number ? $shopClientCart->shopClient->home_number : Html::tag('small', 'Не указан', ['class' => 'text-muted'])?></dd>
            <dt>Параметры регистрации:</dt>
            <dd><?=$shopClientCart->shopClient->shop_users_id ? 'Зарегистрирован ' . date('d.m.Y H:i', $shopClientCart->shopClient->shopUser->user->created_at) : Html::tag('small', 'Не зарегистрирован', ['class' => 'text-muted'])?></dd>
        </dl>

        <legend>Клиент</legend>
        <dl class="dl-horizontal">
            <dt>Имя:</dt>
            <dd><?=$shopClientCart->shopClient->fio ? $shopClientCart->shopClient->fio : Html::tag('small', 'Не указан', ['class' => 'text-muted'])?></dd>
            <dt>Номер телефона:</dt>
            <dd><?=$shopClientCart->shopClient->phone ? $shopClientCart->shopClient->phone : Html::tag('small', 'Не указан', ['class' => 'text-muted'])?></dd>
            <dt>Электронная почта:</dt>
            <dd><?=$shopClientCart->shopClient->email ? $shopClientCart->shopClient->email : Html::tag('small', 'Не указан', ['class' => 'text-muted'])?></dd>
            <dt>Комментарий:</dt>
            <dd><?=$shopClientCart->shopClient->comment ? $shopClientCart->shopClient->comment : Html::tag('small', 'Не указан', ['class' => 'text-muted'])?></dd>
        </dl>
    </div>

    <div class="col-md-7">
        <legend>Заказ</legend>
        <dl class="dl-horizontal">
            <dt>Сумма заказа:</dt>
            <dd><?=preg_replace('/\,00/', '', number_format($price, 2, ',', '&thinsp;'))?> руб.</dd>
            <dt>Количество:</dt>
            <dd><?=$amount?></dd>
        </dl>
        <table class="table table-hover table-condensed">
            <tbody><?=$products?></tbody>
        </table>

        <p><small class="text-muted"><em>* &mdash; Цены указаны на момент покупки</em></small></p>
    </div>
</div>