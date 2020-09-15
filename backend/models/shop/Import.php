<?php
namespace backend\models\shop;

use common\models\gl\GlGroups;
use common\models\gl\GlImgs;
use common\models\gl\GlTypes;
use common\models\sh\ShCharacteristics;
use common\models\sh\ShItemCharacteristics;
use common\models\sh\ShItems;
use Yii;
use yii\base\Model;
use common\models\main\Links;
use common\models\main\Contents;
use common\models\sh\ShGroups;
use common\models\sh\ShGoods;

class Import extends Model
{
    const START_GROUP_PATH = '/КоммерческаяИнформация/Классификатор/Группы/Группа';
    const GROUP_CATEGORIES_ID = 1;
    const GOOD_CATEGORIES_ID = 1;
    const GROUP_LAYOUT = 1;
    const GOOD_LAYOUT = 1;
    const GROUP_VIEW = 1;
    const GOOD_VIEW = 2;

    private $import_file;

    public function parser($import_file)
    {
        $this->import_file = $import_file;
        $sxe = simplexml_load_file($import_file);
        $groups_sxe = $sxe->xpath(self::START_GROUP_PATH);
        if (count($groups_sxe)) $this->parserGroups($groups_sxe);

        $goods_sxe = $sxe->xpath('/КоммерческаяИнформация/Каталог/Товары/Товар');
        if (count($goods_sxe)) $this->parserGoods($goods_sxe);
    }

    private function parserGroups($groups_sxe, $parent=null)
    {
        foreach ($groups_sxe as $item) {
            $group = ShGroups::findOne(['code' => $item->{'Ид'}]);
            $link = $group ? Links::findOne($group->links_id) : new Links();

            $link->categories_id = self::GROUP_CATEGORIES_ID;
            $link->parent = $parent;
            $anchor = strval(isset($item->{'НаименованиеНаСайте'}) && $item->{'НаименованиеНаСайте'} ? trim($item->{'НаименованиеНаСайте'}) : trim($item->{'Наименование'}));
            $link->anchor = preg_replace('/\s+/', ' ', $anchor);
            $link->link_name = isset($item->{'URI'}) && $item->{'URI'} ? strval($item->{'URI'}) : $link->anchor2translit($link->anchor);
            $link_name = $link->link_name;
            $num = 1;
            if (isset($link->id)) {
                while (Links::findByUrlForLink($link->link_name, $link->id, $parent)) {
                    $num += 1;
                    $link->link_name = $link_name.'-'.$num;
                }
            } else {
                while (Links::findByUrl($link->link_name, $parent)) {
                    $num += 1;
                    $link->link_name = $link_name.'-'.$num;
                }
            }
            $link->level = $parent !== null ? Links::findOne($parent)->level + 1 : 1;
            $link->child_exist = 1;
            $link->url = $parent !== null ? Links::findOne($parent)->url.'/'.$link->link_name : '/'.$link->link_name;
            $link->seq = isset($link->id) ? $link->seq : Links::findLastSequence(self::GOOD_CATEGORIES_ID, $parent) + 1;
            $link->title = isset($link->id) ? $link->title : $link->anchor;
            $link->created_at = isset($link->id) ? $link->created_at : time();
            $link->updated_at = time();
            $link->state = $item->{'НеПубликуетсяНаСайте'} == 'истина' ? 0 : 1;
            $link->layouts_id = self::GROUP_LAYOUT;
            $link->views_id = self::GROUP_VIEW;
            $link->content_nums = 1;
            $link->save();

            if ($item->{'Картинки'} && $item->{'Картинки'}->{'Картинка'}) {
                $this->addImage($link->id, $item->{'Картинки'}->{'Картинка'});
            }

            if (!Contents::findOne(['links_id' => $link->id])) {
                $content = new Contents();
                $content->links_id = $link->id;
                $content->seq = 1;
                $content->save();
            }

            if (!$group) {
                $group = new ShGroups();
                $group->links_id = $link->id;
                $group->code = strval($item->{'Ид'});
            };
            $group->groupname = strval($item->{'Наименование'});
            $group->save();

            if ($item->{'Группы'}->{'Группа'}) {
                $this->parserGroups($item->{'Группы'}->{'Группа'}, $link->id);
            }
        }

        return true;
    }

    private function parserGoods($goods_sxe)
    {
        $goods = array();
        foreach ($goods_sxe as $item) {
            $item_code = false;
            if (preg_match('/(.+)#(.+)/', $item->{'Ид'}, $matches)) {
                $good_code = strval($matches[1]);
                $item_code = strval($matches[2]);
            } else {
                $good_code = strval($item->{'Ид'});
            }

            if (!isset($goods[$good_code])) {
                $goods[$good_code] = array();
                $goods[$good_code] = $this->addGood($item, $good_code);
            }

            if ($item_code) {
                $goods[$good_code]['items'][$item_code] = $this->addItem($goods[$good_code]['id'], $item_code, $item);
            }
        }

        foreach ($goods as $good_code => $good) {
            $items = ShItems::findAll(['goods_id' => $good['id']]);
            if (isset($good['items'])) {
                foreach ($items as $item) {
                    if (!isset($good['items'][$item->code])) {
                        $item->state = 0;
                        $item->save();
                    }
                }
            } else {
                foreach ($items as $item) {
                    $item->state = 0;
                    $item->save();
                }
            }
        }

    }

    private function addGood($item, $code)
    {
        $group = ShGroups::findOne(['code' => strval($item->{'Группы'}->{'Ид'})]);
        $good = ShGoods::findOne(['code' => $code]);
        $link = $good ? Links::findOne($good->links_id) : new Links();

        $link->categories_id = self::GOOD_CATEGORIES_ID;
        $link->parent = $group->links_id;
        $link->anchor = strval(isset($item->{'НаименованиеНаСайте'}) && $item->{'НаименованиеНаСайте'} ? $item->{'НаименованиеНаСайте'} : $item->{'Наименование'});
        $link->link_name = isset($item->{'URI'}) && $item->{'URI'} ? strval($item->{'URI'}) : $link->anchor2translit($link->anchor);
        $link_name = $link->link_name;
        $num = 1;
        if (isset($link->id)) {
            while (Links::findByUrlForLink($link->link_name, $link->id, $link->parent)) {
                $num += 1;
                $link->link_name = $link_name.'-'.$num;
            }
        } else {
            while (Links::findByUrl($link->link_name, $link->parent)) {
                $num += 1;
                $link->link_name = $link_name.'-'.$num;
            }
        }
        $link->level = $group->link->level + 1;
        $link->child_exist = 0;
        $link->url = $group->link->url.'/'.$link->link_name;
        $link->seq = isset($link->id) ? $link->seq : Links::findLastSequence(self::GOOD_CATEGORIES_ID, $link->parent) + 1;
        $link->title = isset($link->id) ? $link->title : $link->anchor;
        $link->created_at = isset($link->id) ? $link->created_at : time();
        $link->updated_at = time();
        $link->state = $item->{'НеПубликуетсяНаСайте'} == 'истина' ? 0 : 1;
        $link->layouts_id = self::GOOD_LAYOUT;
        $link->views_id = self::GOOD_VIEW;
        $link->content_nums = 1;
        $link->save();

        $content = Contents::findOne(['links_id' => $link->id]);
        if (!$content) {
            $content = new Contents();
            $content->links_id = $link->id;
            $content->seq = 1;
        }
        $content->content = strval($item->{'Описание'});
        $content->save();

        if (!$good) {
            $good = new ShGoods();
            $good->links_id = $link->id;
            $good->groups_id = $group->id;
            $good->code = $code;
        }
        $good->good = strval($item->{'Наименование'});
        $good->save();

        if ($item->{'Картинки'} && $item->{'Картинки'}->{'Картинка'}) {
            $this->addImage($link->id, $item->{'Картинки'}->{'Картинка'});
        }

        return [
            'id' => $good->id,
            'links_id' => $link->id,
        ];
    }

    private function addImage($links_id, $item)
    {
        $link_imgs_id = Links::findOne($links_id)->gl_imgs_id;
        $gl_groups = array();
        foreach ($item as $image_sxe) {
            $basename_src = basename(strval($image_sxe->{'ПутьКИзображению'}));
            if (!$basename_src) continue;

            preg_match('/_(.+)\s\[(.+)\]/', $basename_src, $matches);
            if ($matches) {
                $type = $matches[1];
                $title = $matches[2];
            } else {
                $type = Yii::$app->params['defaultShGlType'];
                $title = '';
            }


            if (!GlImgs::findOne(['basename_src' => $basename_src])) {
                $gl_type = GlTypes::findOne(['type' => $type]);
                if (!isset($gl_groups[$type])) {
                    $gl_groups[$type] = GlGroups::findOne(['links_id' => $links_id, 'types_id' => $gl_type->id]);
                    if (!$gl_groups[$type]) {
                        $gl_group = new GlGroups();
                        $gl_group->types_id = GlTypes::findOne(['type' => $type])->id;
                        $gl_group->links_id = $links_id;
                        $gl_group->save();
                        $gl_groups[$type] = $gl_group;
                    }
                }


                $src_image = pathinfo($this->import_file)['dirname'].'/'.strval($image_sxe->{'ПутьКИзображению'});
                $dst_path = Yii::getAlias('@frontend').'/web'.$gl_type->dir_dst;
                $images = (new GlImgs())->convertImg($gl_type->id, addslashes($src_image), $dst_path);

                $gl_img = new GlImgs();
                $gl_img->groups_id = $gl_groups[$type]->id;
                $gl_img->img_small = $gl_type->dir_dst.'/'.$images['img_small'];
                $gl_img->img_large = $gl_type->dir_dst.'/'.$images['img_large'];
                $gl_img->basename_src = $basename_src;
                $gl_img->title = $title;
                $gl_img->seq = GlImgs::findLastSequence($gl_groups[$type]->id) + 1;
                $gl_img->save();

                if (!$link_imgs_id) {
                    $link = Links::findOne($links_id);
                    $link->gl_imgs_id = $gl_img->id;
                    $link->save();
                    $link_imgs_id = $gl_img->id;

                    $gl_groups[$type]->imgs_id = $gl_img->id;
                    $gl_groups[$type]->save();
                }

                if ($image_sxe->{'ОсновноеИзображение'} == 1 && $link_imgs_id != $gl_img->id) {
                    $link = Links::findOne($links_id);
                    $link->gl_imgs_id = $gl_img->id;
                    $link->save();
                    $link_imgs_id = $gl_img->id;

                    $gl_groups[$type]->imgs_id = $gl_img->id;
                    $gl_groups[$type]->save();
                }
            }

        }
    }

    private function addItem($goods_id, $code, $item_sxe)
    {
        $item = ShItems::findOne(['code' => $code]);
        if (!$item) {
            $item = new ShItems();
            $item->goods_id = $goods_id;
            $item->code = $code;
        }
        $item->state = 1;
        $item->save();

        foreach (ShItemCharacteristics::findAll(['items_id' => $item->id]) as $item_characteristic) {
            $item_characteristic->state = 0;
            $item_characteristic->save();
        }

        foreach ($item_sxe->{'ХарактеристикиТовара'}->{'ХарактеристикаТовара'} as $characteristic_sxe) {
            $characteristic = ShCharacteristics::findOne(['characteristic' => strval($characteristic_sxe->{'Наименование'})]);
            if (!$characteristic) {
                $characteristic = new ShCharacteristics();
                $characteristic->characteristic = strval($characteristic_sxe->{'Наименование'});
                $characteristic->save();
            }

            $item_characteristic = ShItemCharacteristics::findOne(['items_id' => $item->id, 'characteristics_id' => $characteristic->id]);
            if (!$item_characteristic) {
                $item_characteristic = new ShItemCharacteristics();
                $item_characteristic->items_id = $item->id;
                $item_characteristic->characteristics_id = $characteristic->id;
            }
            $item_characteristic->value = strval($characteristic_sxe->{'Значение'});
            $item_characteristic->state = 1;
            $item_characteristic->save();
        }

        return [
            'id' => $item->id,
        ];
    }
}