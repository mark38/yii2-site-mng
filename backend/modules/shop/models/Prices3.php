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

class Prices3 extends Model
{
    public function parser($price_file)
    {
        $sxe = new \SimpleXMLElement(file_get_contents($price_file));

        $namespaces = $sxe->getNamespaces(true);
        $sxe->registerXPathNamespace('CML', $namespaces['']);
        $pricesSxe = $sxe->xpath('//CML:КоммерческаяИнформация/CML:ПакетПредложений/CML:Предложения/CML:Предложение');

        if (count($pricesSxe)) {
            $this->parserPrices($pricesSxe);
        }
    }

    function parserPrices($pricesSxe)
    {
        $goodPrices = array();
        $goodMinPrice = array();

        $priceTypes = ArrayHelper::map(ShopPriceTypes::find()->all(), 'verification_code', 'id');

        foreach ($pricesSxe as $item) {
            if (preg_match('/(.+)#(.+)/', $item->{'Ид'}, $matches)) {
                $goodVerificationCode = strval($matches[1]);
                $itemVerificationCode = strval($matches[2]);
            } else {
                $goodVerificationCode = strval($item->{'Ид'});
            }

            if (!isset($goodPrices[$goodVerificationCode])) {
                $goodMinPrice[$goodVerificationCode] = false;
                $prices = ShopPriceGood::find()
                    ->innerJoinWith('shopGood')
                    ->where(['verification_code' => $goodVerificationCode])
                    ->all();
                $goodPrices[$goodVerificationCode] = ArrayHelper::map($prices, 'shop_price_types_id', 'price');
            }

            foreach ($item->{'Цены'}->{'Цена'} as $price) {
                $priceVerificationCode = strval($price->{'ИдТипаЦены'});
                $priceTypeId = $priceTypes[$priceVerificationCode];
                $priceValue = strval($price->{'ЦенаЗаЕдиницу'});

                if ($itemVerificationCode) {
                    $priceItem = ShopPriceItem::find()
                        ->innerJoinWith('shopItem')
                        ->where(['shop_items.verification_code' => $itemVerificationCode])
                        ->andWhere(['shop_price_types_id' => $priceTypeId])
                        ->one();

                    if (!$goodMinPrice[$goodVerificationCode] || !$goodMinPrice[$goodVerificationCode][$priceTypeId] || ($goodMinPrice[$goodVerificationCode][$priceTypeId] && $goodMinPrice[$goodVerificationCode][$priceTypeId] > $priceValue)) {
                        $goodMinPrice[$goodVerificationCode][$priceTypeId] = $priceValue;
                    }
                    if ($priceItem && $priceValue != $priceItem->price) {
                        $priceItem->price = $priceValue;
                        $priceItem->save();
                    } elseif (!$priceItem) {

                    }
                }
            }
        }

        if ($goodMinPrice) {
            foreach ($goodMinPrice as $goodVerificationCode => $goodPrice) {
                foreach ($goodPrice as $priceType => $price) {
                    $priceGood = ShopPriceGood::find()
                        ->innerJoinWith('shopGood')
                        ->where(['shop_goods.verification_code' => $goodVerificationCode])
                        ->andWhere(['shop_price_types_id' => $priceType])
                        ->one();

                    if ($priceGood && $priceGood->price != $price) {
                        $priceGood->price = $price;
                        $priceGood->save();
                    } elseif (!$priceGood) {
                        $shopGood = ShopGoods::findOne(['verification_code' => $goodVerificationCode]);
                        if ($shopGood) {
                            $priceGood = new ShopPriceGood();
                            $priceGood->shop_goods_id = $shopGood->id;
                            $priceGood->shop_price_types_id = $priceType;
                            $priceGood->price = $price;
                            $priceGood->save();
                        }
                    }
                }
            }
        }
    }

}