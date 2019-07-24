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
                echo $goodVerificationCode.'<br>';
            }

            $good = ShopGoods::findOne(['verification_code' => $goodVerificationCode]);
            if (!$good) {

            }

            if (!$good) continue;

            if ($itemVerificationCode) { // Товар с характеристикой
                $shopItem = ShopItems::findOne(['verification_code' => $itemVerificationCode]);
                if (!$shopItem) { // Обновление 1с-кодов
                    $chFullName = $item->{'Наименование'};
                    echo '$chFullName: '.$chFullName.'<br>';
                    echo $good->name.'<br />';
                    $chName = preg_replace('/'.$good->name.' \(/', '', $chFullName);
                    $chName = preg_replace('/\)$/', '', $chName);

                    echo $chName.'<br>';

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
            } else {

            }


//            echo $good->name.'<br />';
//            echo $good->id.'<br />';
        }
    }
}