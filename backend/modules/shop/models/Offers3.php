<?php

namespace app\modules\shop\models;

use common\models\shop\ShopGoods;
use common\models\shop\ShopItemCharacteristics;
use common\models\shop\ShopItems;
use common\models\shop\ShopPriceGood;
use common\models\shop\ShopPriceItem;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use common\models\shop\ShopPriceTypes;

class Offers3 extends Model
{
    private $offers_file;

    public function parser($offers_file)
    {
        $sxe = new \SimpleXMLElement(file_get_contents($offers_file));

        $namespaces = $sxe->getNamespaces(true);
        $sxe->registerXPathNamespace('CML', $namespaces['']);
        $offersSxe = $sxe->xpath('//CML:КоммерческаяИнформация/CML:ПакетПредложений/CML:Предложения/CML:Предложение');

        if (count($offersSxe)) {
            $this->parserOffers($offersSxe);
        }
    }

    function parserOffers($offersSxe) {

        foreach ($offersSxe as $item) {
            $itemVerificationCode = false;

            if (preg_match('/(.+)#(.+)/', $item->{'Ид'}, $matches)) {
                $goodVerificationCode = strval($matches[1]);
                $itemVerificationCode = strval($matches[2]);
            } else {
                $goodVerificationCode = strval($item->{'Ид'});
            }

            $good = ShopGoods::findOne(['verification_code' => $goodVerificationCode]);
            if (!$good) continue;

            if ($itemVerificationCode) { // Товар с характеристикой
                $shopItem = ShopItems::findOne(['verification_code' => $itemVerificationCode]);
                if (!$shopItem) { // Обновление 1с-кодов
                    $chFullName = $item->{'Наименование'};
                    $goodName = preg_quote($good->name);
                    $goodName = str_replace("/", "\/", $goodName);

                    $chName = preg_replace("/$goodName \(/", '', $chFullName);
                    $chName = preg_replace('/\)/', '', $chName);

                    $ch = ShopItemCharacteristics::find()
                        ->innerJoinWith('shopItem')
                        ->where(['name' => $chName])
                        ->andWhere(['shop_goods_id' => $good->id])
                        ->one();

                    if ($ch) {
                        $shopItem = ShopItems::findOne($ch->shop_items_id);
                        $shopItem->verification_code = $itemVerificationCode;
                        $shopItem->save();
                    }
                }

                $priceItems = ShopPriceItem::find()->innerJoinWith('shopItem')->where(['shop_items.verification_code' => $itemVerificationCode])->all();
                if ($priceItems) ShopPriceItem::deleteAll(['id' => ArrayHelper::getColumn($priceItems, 'id')]);
            } else {}

            $priceGood = ShopPriceGood::find()->innerJoinWith('shopGood')->where(['shop_goods.verification_code' => $goodVerificationCode])->all();
            if ($priceGood) ShopPriceGood::deleteAll(['id' => ArrayHelper::getColumn($priceGood, 'id')]);
        }


        $priceTypes = ArrayHelper::map(ShopPriceTypes::find()->all(), 'verification_code', 'id');
        $goodMinPrice = array();

        foreach ($offersSxe as $itemSxe) {
            if (preg_match('/(.+)#(.+)/', $itemSxe->{'Ид'}, $matches)) {
                $goodVrfCode = strval($matches[1]);
                $itemVrfCode = strval($matches[2]);
            } else {
                $goodVrfCode = strval($itemSxe->{'Ид'});
            }

            if (!isset($goodMinPrice[$goodVrfCode])) $goodMinPrice[$goodVrfCode] = array();

            foreach ($itemSxe->{'Цены'}->{'Цена'} as $itemPrice) {
                $priceVrfCode = strval($itemPrice->{'ИдТипаЦены'});
                if (!isset($goodMinPrice[$goodVrfCode][$priceTypes[$priceVrfCode]])) {
                    $goodMinPrice[$goodVrfCode][$priceTypes[$priceVrfCode]] = floatval($itemPrice->{'ЦенаЗаЕдиницу'});
                } else if ($goodMinPrice[$goodVrfCode][$priceTypes[$priceVrfCode]] > floatval($itemPrice->{'ЦенаЗаЕдиницу'})) {
                    $goodMinPrice[$goodVrfCode][$priceTypes[$priceVrfCode]] = floatval($itemPrice->{'ЦенаЗаЕдиницу'});
                }

                if ($itemVrfCode) {
                    $itemShop = ShopItems::findOne(['verification_code' => $itemVrfCode]);
                    if ($itemShop) {
                        $priceItem = new ShopPriceItem();
                        $priceItem->shop_items_id = $itemShop->id;
                        $priceItem->shop_price_types_id = $priceTypes[$priceVrfCode];
                        $priceItem->price = floatval($itemPrice->{'ЦенаЗаЕдиницу'});
                        $priceItem->save();
                    }
                }
            }
        }

        foreach ($goodMinPrice as $good_verification_code => $goodPrices) {
            $good = ShopGoods::findOne(['verification_code' => $good_verification_code]);
            if ($good) {
                foreach ($goodPrices as $priceTypesId => $goodPrice) {
                    $price_good = new ShopPriceGood();
                    $price_good->shop_goods_id = $good->id;
                    $price_good->shop_price_types_id = $priceTypesId;
                    $price_good->price = $goodPrice;
                    $price_good->save();
                }
            }
        }
    }
}