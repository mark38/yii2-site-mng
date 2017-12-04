<?php
/**
 * @var \yii\web\View $this
 * @var array $categories
 * @var \common\models\shop\ShopGoods $goods
 */
use kartik\helpers\Html;

$this->title = 'Управление выгрузкой прайс-листов в Яндекс-Маркет';

$categoriesYml = '';
foreach ($categories as $id => $category) {
    $parent = $category['parentId'] !== null ? ' parentId="'.$category['parentId'].'"' : null;
    $categoriesYml .= '        <category id="'.$id.'"'.$parent.'>'.$category['name'].'</category>' . PHP_EOL;
}

$offersYml = '';
/**
 * @var integer $id
 * @var \common\models\shop\ShopGoods $good
 */
foreach ($goods as $id => $good) {
    $prices = $good->shopPriceGoods;
    $offerPrice = null;
    $offerOldPrice = null;
    $offerOldPriceYml = null;
    foreach ($prices as $goodPrice) {
        $price = preg_replace('/\.00/', '', $goodPrice->price);
        if (!$offerPrice) {
            $offerPrice = $price;
        } else {
            if ($price < $offerPrice) {
                $offerOldPrice = $offerPrice;
                $offerPrice = $price;
            } else {
                $offerOldPrice = $price;
            }
        }
    }

    if (!$offerPrice) continue;

    if ($offerOldPrice) $offerOldPriceYml = '            <oldprice>'.$offerOldPrice.'</oldprice>' . PHP_EOL;

    $picture = $good->link->gallery_images_id ? Yii::$app->request->hostInfo.$good->link->galleryImage->large : null;
    $content = $good->link->contents[0]->text ? '<![CDATA['.$good->link->contents[0]->text.']]>' : '';

    $offersYml .= '        <offer id="'.$good->id.'" available="true" bid="" cbid="" fee="">' . PHP_EOL .
                  '            <url>'.Yii::$app->request->hostInfo . $good->link->url.'</url>' . PHP_EOL .
                  '            <price>'.$offerPrice.'</price>' . PHP_EOL . $offerOldPriceYml .
                  '            <currencyId>RUR</currencyId>' . PHP_EOL .
                  '            <categoryId>'.$good->shop_groups_id.'</categoryId>' . PHP_EOL .
                  '            <picture>'.$picture.'</picture>' . PHP_EOL .
                  '            <store>false</store>' . PHP_EOL .
                  '            <pickup>true</pickup>' . PHP_EOL .
                  '            <delivery>true</delivery>' . PHP_EOL .
                  '            <name>'.$good->name.'</name>' . PHP_EOL .
                  '            <description>'.$content.'</description>' . PHP_EOL .
                  '            <sales_notes></sales_notes>' . PHP_EOL .
                  '            <manufacturer_warranty>true</manufacturer_warranty>' . PHP_EOL .
                  '            <country_of_origin></country_of_origin>' . PHP_EOL .
                  '            <barcode></barcode>' . PHP_EOL .
                  '            <cpa>0</cpa>' . PHP_EOL .
                  '            <rec></rec>' . PHP_EOL .
                  '        </offer>' . PHP_EOL;
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
    '<yml_catalog date="'.date('Y-m-d H:i').'">' . PHP_EOL .
    '    <shop>' . PHP_EOL .
    '    <name>'.Yii::$app->name.'</name>' . PHP_EOL .
    '    <company>'.preg_replace('/"/', '&quot;', Yii::$app->params['companyName']).'</company>' . PHP_EOL .
    '    <url>'.Yii::$app->request->hostInfo.'</url>' . PHP_EOL .
    '    <currencies>' .PHP_EOL .
    '        <currency id="RUR" rate="1"/>' . PHP_EOL .
    '        <currency id="USD" rate="CBRF" plus="3"/>' . PHP_EOL .
    '        <currency id="EUR" rate="CBRF" plus="3"/>' . PHP_EOL .
    '    </currencies>' . PHP_EOL .
    '    <categories>' . PHP_EOL . $categoriesYml .
    '    </categories>' . PHP_EOL .
    '    <offers>' . PHP_EOL . $offersYml .
    '    </offers>' . PHP_EOL .
    '    </shop>' . PHP_EOL .
    '</yml_catalog>';
?>

