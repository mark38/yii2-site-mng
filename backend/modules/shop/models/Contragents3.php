<?php

namespace app\modules\shop\models;

use common\models\helpers\Translit;
use common\models\shop\ShopGoods;
use common\models\shop\ShopItemCharacteristics;
use common\models\shop\ShopItems;
use common\models\shop\ShopPriceGood;
use common\models\shop\ShopPriceItem;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use common\models\shop\ShopPriceTypes;

class Contragents3 extends Model
{
    public function parser($xmlFile)
    {
        $sxe = new \SimpleXMLElement(file_get_contents($xmlFile));

        $namespaces = $sxe->getNamespaces(true);
        $sxe->registerXPathNamespace('CML', $namespaces['']);
        $contragentsSxe = $sxe->xpath('//CML:КоммерческаяИнформация/CML:Контрагенты/CML:Контрагент');

        if (count($contragentsSxe)) {
            foreach ($contragentsSxe as $i => $item) {
                if ($i == 0) {
                    echo 'LAST_NAME;NAME;LOGIN;PASSWORD;EMAIL;INN<br />';
                }

                $email = false;
                if ($item->{'Контакты'}) {
                    foreach ($item->{'Контакты'}->{'Контакт'} as $contact) {
                        if ($contact->{'Тип'} == 'Электронная почта') {
                            $email = $contact->{'Значение'};
                        }
                    }
                }
                $inn = $item->{'ИНН'} ?: '';

                $login = (new Translit())->translitToString($item->{'ПолноеНаименование'});
                echo $item->{'Наименование'}.';'.$item->{'ПолноеНаименование'}.';'.$login.';'.$login.';'.$email.';'.$inn.'<br />';
            }
        }
    }
}