<?php

namespace backend\modules\shop\models;

use frontend\widgets\shop\Group;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\imagine\Image;
use backend\models\Helpers;
//use mark38\galleryManager\Gallery;
use backend\widgets\gallery\Gallery;
use common\models\helpers\Translit;
use common\models\main\Contents;
use common\models\main\Links;
use common\models\shop\ShopCharacteristics;
use common\models\shop\ShopGoods;
use common\models\shop\ShopGroups;
use common\models\shop\ShopItemCharacteristics;
use common\models\shop\ShopItems;
use common\models\shop\ShopGoodGallery;
use common\models\shop\ShopGoodProperties;
use common\models\shop\ShopGroupProperties;
use common\models\shop\ShopProperties;
use common\models\shop\ShopPropertyValues;
use common\models\gallery\GalleryGroups;
use common\models\gallery\GalleryImages;
use common\models\gallery\GalleryTypes;
use common\models\shop\ShopUnits;

class Import3 extends Model
{
    private $import_file;
    private $fullPath;
    private $uploadLog = false;
    private $shop_groups = array();
    private $shop_properties = array();

    public function parser($import_file, $uploadLog=false)
    {
        $this->fullPath = dirname($import_file);
        $sxe = new \SimpleXMLElement(file_get_contents($import_file));

        $namespaces = $sxe->getNamespaces(true);
        $sxe->registerXPathNamespace('CML', $namespaces['']);
        $path = preg_replace('/\//', '/CML:', Yii::$app->params['shop']['startGroupPath']);

        $groupsSxe = $sxe->xpath('/'.$path);
        $goodsSxe = $sxe->xpath('//CML:КоммерческаяИнформация/CML:Каталог/CML:Товары/CML:Товар');
        $propertiesSxe = $sxe->xpath('//CML:КоммерческаяИнформация/CML:Классификатор/CML:Свойства/CML:Свойство');

        if (count($groupsSxe)) {
            $parentLink = Links::findOne(['url' => Yii::$app->params['shop']['catalogUrl']]);
            $parentLinksId = $parentLink ? $parentLink->id : null;
            $this->parserGroups($groupsSxe, $parentLinksId);

            $unitsSxe = $sxe->xpath('//CML:КоммерческаяИнформация/CML:Классификатор/CML:ЕдиницыИзмерения/CML:ЕдиницаИзмерения');
            $this->parserUnits($unitsSxe);
        }

        if (count($propertiesSxe)) {
            $this->parserProperties($propertiesSxe);
        }

        if (count($goodsSxe)) {
            $this->shop_properties = ShopProperties::find()->where(['is not', 'verification_code', null])->all();
            $this->parserGoods($goodsSxe);
        }

        return true;
    }

    public function parserGroups($groupsSxe, $parentLinksId=null, $parentGroupsId=null)
    {
        foreach ($groupsSxe as $item) {
            $group = ShopGroups::find()->where(['verification_code' => $item->{'Ид'}])->one();
            if (!$group) {
                $group = ShopGroups::find()->where(['name' => $item->{'Наименование'}])->one();
                if ($group) {
                    $group->verification_code = strval($item->{'Ид'});
                    $group->save();
                }
            }

            $link = $group && $group->links_id ? Links::findOne($group->links_id) : new Links();
            $translit = new Translit();

            $link->categories_id = Yii::$app->params['shop']['categoriesId'];

            $link->parent = $parentLinksId;
            $link->anchor = strval($item->{'Наименование'});
            $link->name = isset($link->id) ? $translit->slugify($link->anchor, $link->tableName(), 'name', '-', $link->id) : $translit->slugify($link->anchor, $link->tableName(), 'name', '-', null);
            $link->level = $parentLinksId !== null ? Links::findOne($parentLinksId)->level + 1 : 1;
            $link->url = (new Links())->getPrefixUrl(Yii::$app->params['shop']['groupUrlPrefix'], $link->level, $parentLinksId).'/'.$link->name;
            $link->child_exist = 1;
            $link->seq = isset($link->id) ? $link->seq : Links::findLastSequence(Yii::$app->params['shop']['categoriesId'], $parentLinksId) + 1;
            $link->title = isset($link->id) ? $link->title : $link->anchor;
            $link->created_at = isset($link->id) ? $link->created_at : time();
            $link->updated_at = time();
            $link->state = $item->{'ПометкаУдаления'} == 'false' ? 1 : 0;
            $link->layouts_id = Yii::$app->params['shop']['groupLayoutsId'];
            $link->views_id = Yii::$app->params['shop']['groupViewsId'];
            $link->save();

            if (!Contents::findOne(['links_id' => $link->id])) {
                $content = new Contents();
                $content->links_id = $link->id;
                $content->seq = 1;
                $content->save();
            }

            if (!$group) {
                $group = new ShopGroups();
                $group->verification_code = strval($item->{'Ид'});
            };
            $group->links_id = $link->id;
            $group->name = strval($item->{'Наименование'});
            $group->parent_id = $parentGroupsId;
            $group->save();
            $this->shop_groups[] = $group;

            ShopGoods::updateAll(['state' => 0], ['shop_groups_id' => $group->id]);

            if ($item->{'Группы'}->{'Группа'}) {
                $this->parserGroups($item->{'Группы'}->{'Группа'}, $link->id, $group->id);
            }
        }

        return true;
    }

    public function parserUnits($unitsSxe)
    {
        foreach ($unitsSxe as $item) {
            $code = trim($item->{'Код'});
            $shortName = trim($item->{'НаименованиеКраткое'});
            $unit = ShopUnits::findOne(['code' => $code]);
            if (!$unit) {
                $unit = ShopUnits::findOne(['name' => $shortName, 'code' => null]);
                if ($unit) {
                    $unit->code = $code;
                    $unit->save();
                } else {
                    $unit = new ShopUnits();
                    $unit->name = $shortName;
                    $unit->code = $code;
                    $unit->save();
                }
            } else {
                $unit->name = $shortName;
                $unit->save();
            }
        }
    }

    public function parserProperties($propertiesSxe)
    {
        foreach ($propertiesSxe as $item) {
            $nameProperty = trim(strval($item->{'Наименование'}));

            $shopProperty = ShopProperties::findOne(['verification_code' => $item->{'Ид'}]);
            $actionUpdate = false;
            if (!$shopProperty) {
                $shopProperty = new ShopProperties();
                $actionUpdate = true;
                $shopProperty->verification_code = strval($item->{'Ид'});
                $shopProperty->seq = $shopProperty->findLastSequence() + 1;
            } else {
                if ($shopProperty->name != $nameProperty) {
                    $actionUpdate = true;
                }
            }

            if ($actionUpdate) {
                $currentId = $shopProperty ? $shopProperty->id : null;
                $shopProperty->state = $item->{'ПометкаУдаления'} == 'false' ? 1 : 0;
                $shopProperty->name = $nameProperty;
                $shopProperty->anchor = strval($item->{'Наименование'});
                $shopProperty->url = (new Translit())->slugify($item->{'Наименование'}, $shopProperty->tableName(), 'url', '_', $currentId);
                $shopProperty->save();
            }


            $this->shop_properties[] = $shopProperty;

            if ($item->{'ВариантыЗначений'}->{'Справочник'}) {
                foreach ($item->{'ВариантыЗначений'}->{'Справочник'} as $itemPropertyGood) {
                    $nameValue = trim(strval($itemPropertyGood->{'Значение'}));

                    $shopPropertyValue = ShopPropertyValues::findOne(['verification_code' => $itemPropertyGood->{'ИдЗначения'}]);
                    $actionUpdate = false;
                    if (!$shopPropertyValue) {
                        $actionUpdate = true;
                        $shopPropertyValue = new ShopPropertyValues();
                        $shopPropertyValue->shop_properties_id = $shopProperty->id;
                        $shopPropertyValue->verification_code = strval($itemPropertyGood->{'ИдЗначения'});
                    } else {
                        if ($shopPropertyValue->name != $nameValue) {
                            $actionUpdate = true;
                        }
                    }

                    if ($actionUpdate) {
                        $shopPropertyValue->name = $nameValue;
                        $shopPropertyValue->anchor = $nameValue;
                        $shopPropertyValue->url = (new Translit())->slugify($nameValue, $shopPropertyValue->tableName(), 'url', '_', null, 'shop_properties_id', $shopProperty->id);
                        $shopPropertyValue->save();
                    }
                }
            }
        }

        /** @var ShopGroups $shopGroup */
        foreach ($this->shop_groups as $shopGroup) {
            /** @var ShopProperties $shopProperty */
            foreach ($this->shop_properties as $shopProperty) {
                if (!ShopGroupProperties::findOne(['shop_groups_id' => $shopGroup->id, 'shop_properties_id' => $shopProperty->id])) {
                    $shopGroupProperty = new ShopGroupProperties();
                    $shopGroupProperty->shop_groups_id = $shopGroup->id;
                    $shopGroupProperty->shop_properties_id = $shopProperty->id;
                    $shopGroupProperty->save();
                }
            }
        }

        return true;
    }

    public function parserPropertiesDef($properties_sxe)
    {
        foreach ($properties_sxe as $item) {
            $shop_property = ShopProperties::findOne(['verification_code' => $item->{'Ид'}]);

            if (!$shop_property) {
                $shop_property = new ShopProperties();
                $shop_property->seq = $shop_property->findLastSequence() + 1;
                $currentId = null;
            } else {
                $currentId = $shop_property->id;
            }

            $shop_property->verification_code = strval($item->{'Ид'});
            $shop_property->name = strval($item->{'Наименование'});
            $shop_property->anchor = strval($item->{'Наименование'});
            $shop_property->url = (new Translit())->slugify($item->{'Наименование'}, $shop_property->tableName(), 'url', '_', $currentId);
            $shop_property->save();
            $this->shop_properties[] = $shop_property;
        }

        /** @var $shop_group ShopGroups */
        foreach ($this->shop_groups as $shop_group) {
            /** @var $shop_property ShopProperties */
            foreach ($this->shop_properties as $shop_property) {
                if (!ShopGroupProperties::findOne(['shop_groups_id' => $shop_group->id, 'shop_properties_id' => $shop_property->id])) {
                    $shop_group_property = new ShopGroupProperties();
                    $shop_group_property->shop_groups_id = $shop_group->id;
                    $shop_group_property->shop_properties_id = $shop_property->id;
                    $shop_group_property->save();
                }
            }
        }

        return true;
    }

    public function parserGoods($goodsSxe)
    {
        $goods = array();
        foreach ($goodsSxe as $item) {
            $itemVerificationCode = false;
            $itemsExist = 0;

            $groupVerificationCode = strval($item->{'Группы'}->{'Ид'});
            $goodVerificationCode = strval($item->{'Ид'});
            $goodName = strval($item->{'Наименование'});

            $group = ShopGroups::findOne(['verification_code' => $groupVerificationCode]);
            if (!$group) continue;

            $good = ShopGoods::findOne(['verification_code' => $goodVerificationCode]);
            if (!$good) {
                $good = ShopGoods::findOne(['name' => $goodName, 'shop_groups_id' => $group->id]);
                if ($good) {
                    $good->verification_code = $goodVerificationCode;
                    $good->save();
                }
            }

            $goods[$goodVerificationCode] = $this->modifyGood($item, $goodVerificationCode, $group, $itemsExist);
            $this->modifyGoodProperty($item, $goods[$goodVerificationCode]['good']);
        }
    }

    public function parserGoodsDef($goodsSxe)
    {
        $goods = array();
        foreach ($goodsSxe as $item) {
            $groupVerificationCode = strval($item->{'Группы'}->{'Ид'});
            $goodVerificationCode = strval($item->{'Ид'});
            $goodName = strval($item->{'Наименование'});

            $group = ShopGroups::findOne(['verification_code' => $groupVerificationCode]);
            if (!$group) continue;

            $good = ShopGoods::findOne(['verification_code' => $goodVerificationCode]);
            if (!$good) {
                $good = ShopGoods::findOne(['name' => $goodName, 'shop_groups_id' => $group->id]);
                if ($good) {
                    $good->verification_code = $goodVerificationCode;
                    $good->save();
                }
            }
        }
    }

    private function modifyGood($item, $verificationCode, $group, $itemsExist=0)
    {
        $good = ShopGoods::findOne(['verification_code' => $verificationCode]);
        if ($good) $link = Links::findOne($good->links_id);
        if (!isset($link)) $link = new Links();
        $translit = new Translit();

        $link->categories_id = Yii::$app->params['shop']['categoriesId'];
        $link->parent = $group->links_id;
        $link->anchor = strval($item->{'Наименование'});
        $link->name = isset($link->id) ? $translit->slugify($link->anchor, $link->tableName(), 'name', '-', $link->id) : $translit->slugify($link->anchor, $link->tableName(), 'name', '-', null);
        $link->level = $group->link->level + 1;
        $link->url = (new Links())->getPrefixUrl(Yii::$app->params['shop']['goodUrlPrefix'], $link->level, $group->links_id).'/'.$link->name;
        $link->child_exist = 0;
        $link->seq = isset($link->id) ? $link->seq : Links::findLastSequence(Yii::$app->params['shop']['categoriesId'], $link->parent) + 1;
        $link->title = isset($link->id) ? $link->title : $link->anchor;
        $link->created_at = isset($link->id) ? $link->created_at : time();
        $link->updated_at = time();
        $link->state = $item->{'ПометкаУдаления'} == 'false' ? 1 : 0;
        $link->layouts_id = Yii::$app->params['shop']['productLayoutsId'];
        $link->views_id = Yii::$app->params['shop']['productViewsId'];
        $link->save();

        $content = Contents::findOne(['links_id' => $link->id]);
        if (!$content) {
            $content = new Contents();
            $content->links_id = $link->id;
            $content->seq = 1;
        }
        $content->text = strval($item->{'Описание'});
        $content->save();

        if (!$good) {
            $good = new ShopGoods();
            $good->shop_groups_id = $group->id;
            $good->verification_code = $verificationCode;
        }
        $good->links_id = $link->id;

        $shopUnit = ShopUnits::findOne(['code' => trim($item->{'БазоваяЕдиница'})]);
        if (!$shopUnit) {
            $shopUnit = new ShopUnits();
            $shopUnit->code = strval($item->{'БазоваяЕдиница'});
            $shopUnit->save();
        }

        $good->shop_units_id = $shopUnit->id;
        $good->name = strval($item->{'Наименование'});

        $good->code = '';

        foreach ($item->{'ЗначенияРеквизитов'}->{'ЗначениеРеквизита'} as $prop) {
            switch ($prop->{'Наименование'}) {
                case "Код": $good->code = preg_replace('/^\D+0*/', '', $prop->{'Значение'}); break;
                case "Файл": $this->addImage($prop->{'Значение'}, Yii::$app->params['shop']['gallery']['good'], $link->id, $good->id); break;
            }
        }

        $good->state = $link->state;
        $good->items_exist = $itemsExist;
        $good->save();

        if ($item->{'Картинка'}) {
            $this->addImage($item, Yii::$app->params['shop']['gallery']['good'], $link->id, $good->id);
        }

        return [
            'id' => $good->id,
            'links_id' => $link->id,
            'good' => $good,
        ];
    }

    /**
     * @param \SimpleXMLElement $item
     * @param ShopGoods $good
     */
    private function modifyGoodProperty($item, $good)
    {
        $shopProperties = ArrayHelper::index($this->shop_properties, 'verification_code');
        $goodProperties = ArrayHelper::index(ShopGoodProperties::find()->where(['shop_goods_id' => $good->id])->all(), 'shop_properties_id');
        $goodPropertiesArr = array();

        foreach ($goodProperties as $shopPropertiesId => $goodProperty) {
            $goodPropertiesArr[$shopPropertiesId]['exist'] = false;
            $goodPropertiesArr[$shopPropertiesId]['id'] = $goodProperty['id'];
        }

        foreach ($item->{'ЗначенияСвойств'}->{'ЗначенияСвойства'} as $itemProperty) {
            $verificationCode = trim(strval($itemProperty->{'Ид'}));
            $verificationCodeValue = trim(strval($itemProperty->{'Значение'}));
            if (isset($shopProperties[$verificationCode])) {
                $shopProperty = $shopProperties[$verificationCode];
                $shopPropertyValue = ShopPropertyValues::findOne([
                    'shop_properties_id' => $shopProperty['id'],
                    'verification_code' => $verificationCodeValue
                ]);

                if ($shopPropertyValue) {
                    if (isset($goodProperties[$shopProperty['id']])) {
                        $goodPropertiesArr[$shopProperty['id']]['exist'] = true;
                        ShopGoodProperties::updateAll(['shop_property_values_id' => $shopPropertyValue->id], ['shop_goods_id' => $goodProperties[$shopProperty['id']]['shop_goods_id'], 'shop_properties_id' => $shopProperty['id']]);
                    } else {
                        $shopGoodProperty = new ShopGoodProperties();
                        $shopGoodProperty->shop_goods_id = $good->id;
                        $shopGoodProperty->shop_properties_id = $shopProperty['id'];
                        $shopGoodProperty->shop_property_values_id = $shopPropertyValue->id;
                        $shopGoodProperty->save();
                    }
                }
            }
        }

        if (isset($goodPropertiesArr)) {
            foreach ($goodPropertiesArr as $shopPropertiesId => $goodProperty) {
                if (isset($goodProperty['exist']) && $goodProperty['exist'] != true) {
                    ShopGoodProperties::deleteAll(['id' => $goodProperty['id']]);
                }
            }
        }
    }

    private function addGoodDef($item, $verificationCode, $itemsExist=0)
    {
        $group = ShopGroups::findOne(['verification_code' => strval($item->{'Группы'}->{'Ид'})]);
        $good = ShopGoods::findOne(['verification_code' => $verificationCode]);
        if ($good) $link = Links::findOne($good->links_id);
        if (!isset($link)) $link = new Links();
        $translit = new Translit();

        $link->categories_id = Yii::$app->params['shop']['categoriesId'];
        $link->parent = $group->links_id;
        $link->anchor = strval(isset($item->{'НаименованиеНаСайте'}) && $item->{'НаименованиеНаСайте'} ? $item->{'НаименованиеНаСайте'} : $item->{'Наименование'});
        $link->name = isset($link->id) ? $translit->slugify($link->anchor, $link->tableName(), 'name', '-', $link->id) : $translit->slugify($link->anchor, $link->tableName(), 'name', '-', null);
        $link->level = $group->link->level + 1;
        $link->url = (new Links())->getPrefixUrl(Yii::$app->params['shop']['goodUrlPrefix'], $link->level, $group->links_id).'/'.$link->name;
        $link->child_exist = 0;
        $link->seq = isset($link->id) ? $link->seq : Links::findLastSequence(Yii::$app->params['shop']['categoriesId'], $link->parent) + 1;
        $link->title = isset($link->id) ? $link->title : $link->anchor;
        $link->created_at = isset($link->id) ? $link->created_at : time();
        $link->updated_at = time();
        $link->state = $item->{'НеПубликуетсяНаСайте'} == 'истина' ? 0 : 1;
        $link->layouts_id = Yii::$app->params['shop']['productLayoutsId'];
        $link->views_id = Yii::$app->params['shop']['productViewsId'];
        $link->save();

        $content = Contents::findOne(['links_id' => $link->id]);
        if (!$content) {
            $content = new Contents();
            $content->links_id = $link->id;
            $content->seq = 1;
        }
        $content->text = strval($item->{'Описание'});
        $content->save();

        if (!$good) {
            $good = new ShopGoods();
            $good->shop_groups_id = $group->id;
            $good->verification_code = $verificationCode;
        }
        $good->links_id = $link->id;
        $shopUnit = ShopUnits::findOne(['name' => $item->{'БазоваяЕдиница'}]);
        if (!$shopUnit) {
            $shopUnit = new ShopUnits();
            $shopUnit->name = strval($item->{'БазоваяЕдиница'});
            $shopUnit->save();
        }
        $good->shop_units_id = $shopUnit->id;
        $good->name = strval($item->{'Наименование'});
        $good->code = preg_replace('/^\D+0*/', '', $item->{'КодНоменклатуры'});
        $good->state = 1;
        $good->items_exist = $itemsExist;
        $good->save();

        if ($item->{'Картинки'} && $item->{'Картинки'}->{'Картинка'}) {
            $this->addImage($item->{'Картинки'}->{'Картинка'}, Yii::$app->params['shop']['gallery']['good'], $link->id, $good->id);
        }

        return [
            'id' => $good->id,
            'links_id' => $link->id,
        ];
    }

    private function addPropertyValues($propertiesSxe, $goodsId)
    {
        $properties = ArrayHelper::index($this->shop_properties, 'verification_code');
        $goodProperties = ShopGoodProperties::find()->where(['shop_goods_id' => $goodsId])->all();
        if ($goodProperties) {
            ShopGoodProperties::updateAll(['state' => 0], ['id' => ArrayHelper::getColumn($goodProperties, 'id')]);
        }

        foreach ($propertiesSxe as $item) {
            $verification_code = strval($item->{'Ид'});
            $names = preg_split('/\,/', trim(strval($item->{'Значение'})));
            $propertiesId = $properties[$verification_code]->id;

            foreach ($names as $name) {
                $name = trim($name);
                $propertyValue = ShopPropertyValues::findOne(['shop_properties_id' => $propertiesId, 'name' => $name]);
                if (!$propertyValue) {
                    $propertyValue = new ShopPropertyValues();
                    $propertyValue->shop_properties_id = $propertiesId;
                    $propertyValue->name = $name;
                    $propertyValue->anchor = $name;
                    $propertyValue->url = (new Translit())->slugify($name, $propertyValue->tableName(), 'url', '_', null, 'shop_properties_id', $propertiesId);
                    $propertyValue->save();
                }

                $addPropertyValue = true;
                if ($goodProperties) {
                    foreach ($goodProperties as $id => $goodProperty) {
                        if ($goodProperty->shop_properties_id == $propertiesId && $goodProperty->shop_property_values_id == $propertyValue->id) {
                            $addPropertyValue = false;
                            ShopGoodProperties::updateAll(['state' => 1], ['id' => $goodProperty->id]);
                        }
                    }
                }

                if ($addPropertyValue) {
                    $goodProperty = new ShopGoodProperties();
                    $goodProperty->shop_goods_id = $goodsId;
                    $goodProperty->shop_properties_id = $propertiesId;
                    $goodProperty->shop_property_values_id = $propertyValue->id;
                    $goodProperty->state = 1;
                    $goodProperty->save();
                }
            }
        }

        ShopGoodProperties::deleteAll(['shop_goods_id' => $goodsId, 'state' => 0]);
    }

    private function addItem($goods_id, $verification_code, $item_sxe)
    {
        $item = ShopItems::findOne(['verification_code' => $verification_code]);
        if (!$item) {
            $item = new ShopItems();
            $item->shop_goods_id = $goods_id;
            $item->verification_code = $verification_code;
        }
        $item->state = 1;
        $item->save();

        foreach (ShopItemCharacteristics::findAll(['shop_items_id' => $item->id]) as $item_characteristic) {
            $item_characteristic->state = 0;
            $item_characteristic->save();
        }

        if (isset($item_sxe->{'ХарактеристикиТовара'})) {
            foreach ($item_sxe->{'ХарактеристикиТовара'}->{'ХарактеристикаТовара'} as $characteristic_sxe) {
                $characteristic = ShopCharacteristics::findOne(['name' => strval($characteristic_sxe->{'Наименование'})]);
                if (!$characteristic) {
                    $characteristic = new ShopCharacteristics();
                    $characteristic->name = strval($characteristic_sxe->{'Наименование'});
                    $characteristic->save();
                }

                $item_characteristic = ShopItemCharacteristics::findOne(['shop_items_id' => $item->id, 'shop_characteristics_id' => $characteristic->id]);
                if (!$item_characteristic) {
                    $item_characteristic = new ShopItemCharacteristics();
                    $item_characteristic->shop_items_id = $item->id;
                    $item_characteristic->shop_characteristics_id = $characteristic->id;
                }
                $item_characteristic->name = strval($characteristic_sxe->{'Значение'});
                $item_characteristic->state = 1;
                $item_characteristic->save();
            }
        }

        return [
            'id' => $item->id,
        ];
    }

    private function addImage($item, $srcGalleryTypesId, $linksId, $shop_goods_id=null)
    {
        $galleryGroups = array();
        $i = 0;
        if (!$item->{'Картинка'}) {

            $basenameSrc = basename(strval($item));
//            $srcImage = $this->fullPath . pathinfo($this->import_file)['dirname'] . '/' . strval($imageItem);
            $srcImage = $this->fullPath . '/' . strval($item);

            if (is_file($srcImage)) {
                $galleryTypesId = false;
                $alt = '';
                foreach (Yii::$app->params['shop']['gallery'] as $name => $id) {
                    if (!$galleryTypesId && strripos($basenameSrc, '_' . $name) !== false) {
                        $galleryTypesId = $id;
                        preg_match('/\[(.+)\]/', $basenameSrc, $matches);
                        $alt = isset($matches[1]) ? $matches[1] : '';
                    }
                }
                if (!$galleryTypesId) $galleryTypesId = $srcGalleryTypesId;

                if (GalleryTypes::findOne($galleryTypesId)) {
                    $galleryGroups[$galleryTypesId][] = [
                        'name' => $basenameSrc,
                        'src_image' => $srcImage,
                        'alt' => $alt,
                        'default' => $i == 0 ? 1 : 0
                    ];
                }

                $i += 1;
            }

        } else {

            foreach ($item->{'Картинка'} as $imageItem) {
                $basenameSrc = basename(strval($imageItem));
                if (!$basenameSrc) continue;

//            $srcImage = $this->fullPath . pathinfo($this->import_file)['dirname'] . '/' . strval($imageItem);
                $srcImage = $this->fullPath . '/' . strval($imageItem);

                if (!is_file($srcImage)) {
                    continue;
                }

                $galleryTypesId = false;
                $alt = '';
                foreach (Yii::$app->params['shop']['gallery'] as $name => $id) {
                    if (!$galleryTypesId && strripos($basenameSrc, '_' . $name) !== false) {
                        $galleryTypesId = $id;
                        preg_match('/\[(.+)\]/', $basenameSrc, $matches);
                        $alt = isset($matches[1]) ? $matches[1] : '';
                    }
                }
                if (!$galleryTypesId) $galleryTypesId = $srcGalleryTypesId;

                if (GalleryTypes::findOne($galleryTypesId)) {
                    $galleryGroups[$galleryTypesId][] = [
                        'name' => $basenameSrc,
                        'src_image' => $srcImage,
                        'alt' => $alt,
                        'default' => $i == 0 ? 1 : 0
                    ];
                }

                $i += 1;
            }

        }

        if (!$galleryGroups) return false;

        $link = Links::findOne($linksId);

        foreach ($galleryGroups as $gallery_types_id => $images) {

            $gallery_group = false;
            if ($shop_goods_id) {
                $good_gallery = ShopGoodGallery::findOne(['shop_goods_id' => $shop_goods_id, 'gallery_types_id' => $gallery_types_id]);
                if (!$good_gallery) {
                    $gallery_group = new GalleryGroups();
                    $gallery_group->gallery_types_id = $gallery_types_id;
                    $gallery_group->save();

                    $good_gallery = new ShopGoodGallery();
                    $good_gallery->shop_goods_id = $shop_goods_id;
                    $good_gallery->gallery_types_id = $gallery_types_id;
                    $good_gallery->gallery_groups_id = $gallery_group->id;
                    $good_gallery->save();
                } else {
                    $gallery_group = GalleryGroups::findOne($good_gallery->gallery_groups_id);
                }
            } elseif (in_array($gallery_types_id, Yii::$app->params['shop']['galleryLink'])) {
                if (!$link->gallery_images_id) {
                    $gallery_group = new GalleryGroups();
                    $gallery_group->gallery_types_id = $gallery_types_id;
                    $gallery_group->save();
                } else {
                    $gallery_group = GalleryGroups::findOne($link->galleryImage->gallery_groups_id);
                }
            }

            if ($gallery_group) {
                foreach ($images as $index => $image) {
                    $gallery_image = GalleryImages::findOne(['gallery_groups_id' => $gallery_group->id, 'name' => $image['name']]);
                    if (!$gallery_image) {
                        $new_image = $this->saveImage($image['src_image'], $gallery_types_id, $gallery_group->id);
                        if (isset($new_image['small']) && isset($new_image['large'])) {
                            $gallery_image = new GalleryImages();
                            $gallery_image->gallery_groups_id = $gallery_group->id;
                            $gallery_image->small = $new_image['small'];
                            $gallery_image->large = $new_image['large'];
                            $gallery_image->name = $image['name'];
                            $gallery_image->alt = $image['alt'];
                            $gallery_image->seq = $new_image['seq'];
                            $gallery_image->save();
                        }
                    } else {
                        if ($gallery_image->alt != $image['alt']) {
                            $gallery_image->alt = $image['alt'];
                            $gallery_image->update();
                        }
                    }

                    if ($image['default'] == 1) {
                        if ($gallery_group->gallery_images_id != $gallery_image->id) {
                            $gallery_group->gallery_images_id = $gallery_image->id;
                            $gallery_group->update();
                        }

                        if ($gallery_image->id && $link->gallery_images_id != $gallery_image->id) {
                            $link->gallery_images_id = $gallery_image->id;
                            $link->update();
                        }
                    }
                }

                $gallery_images = GalleryImages::find()->where(['gallery_groups_id' => $gallery_group->id])->orderBy(['seq' => SORT_ASC])->all();
                $resort_images = false;
                foreach ($gallery_images as $gallery_image) {
                    $delete_image = true;
                    foreach ($images as $index => $image) {
                        if ($image['name'] == $gallery_image->name) $delete_image = false;
                    }

                    if ($delete_image) {
                        $resort_images = true;
                        GalleryImages::findOne($gallery_image->id)->delete();
                        unlink(Yii::getAlias('@frontend/web').$gallery_image->small);
                        unlink(Yii::getAlias('@frontend/web').$gallery_image->large);
                    }
                }
                if ($resort_images) {
                    $gallery = new Gallery();
                    $gallery->resortImages($gallery_group->id);
                }
            }
        }
    }

    public function saveImage($src_image, $gallery_types_id, $gallery_groups_id)
    {
        $type = GalleryTypes::find()->where(['id' => $gallery_types_id])->asArray()->one();

        $gallery = new Gallery();
        $size = $gallery->getSize($src_image, $type);
        $path = Yii::getAlias('@frontend/web').$type['destination'];
        if (!is_dir($path)) {
            $helper = new Helpers();
            $helper->makeDirectory($path);
        }
        $file_info = new \SplFileInfo($src_image);

        if ($file_info->getExtension() == 'bmp') {
            $newSrcFile = dirname($src_image).'/'.basename($src_image,'.bmp').'.jpg';
            rename($src_image, $newSrcFile);
            $src_image = $newSrcFile;
            $file_info = new \SplFileInfo($src_image);
        } else {
            $newSrcFile = $src_image;
        }

        $image_small = $gallery->renderFilename($path, $file_info->getExtension());
        $image_large = $gallery->renderFilename($path, $file_info->getExtension());

        Image::thumbnail($newSrcFile, $size['small_width'], $size['small_height'])->save($path.'/'.$image_small.'.'.$file_info->getExtension(), ['quality' => $type['quality']]);
        Image::thumbnail($newSrcFile, $size['large_width'], $size['large_height'])->save($path.'/'.$image_large.'.'.$file_info->getExtension(), ['quality' => $type['quality']]);

        return [
            'small' => $type['destination'].'/'.$image_small.'.'.$file_info->getExtension(),
            'large' => $type['destination'].'/'.$image_large.'.'.$file_info->getExtension(),
            'seq' => ($gallery->getLastSequence($gallery_groups_id) + 1)
        ];
    }

    public function decodeImageName($dir)
    {
        foreach (scandir($dir) as $key => $value) {
            if (in_array($value, array('.', '..'))) continue;

            if (is_dir($dir.'/'.$value)) {
                $this->decodeImageName($dir.'/'.$value);
            } else {
                if (exif_imagetype($dir.'/'.$value) > 0) {
                    $new_name = iconv("CP866", "UTF-8", $value);
                    if ($new_name != $value) rename($dir.'/'.$value, $dir.'/'.$new_name);
                }
            }
        }
    }
}