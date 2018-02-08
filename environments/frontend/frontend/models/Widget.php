<?php

namespace frontend\models;

use yii\base\Model;
use yii\bootstrap\Html;

class Widget extends Model
{
    public function renderModule($link, $matches)
    {
        return $this->getReplacement($link, $matches[2], 'modules');
    }

    public function renderWidget($link, $matches)
    {
        return $this->getReplacement($link, $matches[2]);
    }

    public function getReplacement($link, $text, $type=false)
    {
        $type = $type ? '\\'.$type : '';

        preg_match('/(\S+)\((.*)\)/', $text, $matches);
        $path_widget = preg_split('/\//', $matches[1]);

        if (count($path_widget) > 1) {
            $widget_name = '\\frontend\\widgets'.$type;
            for ($i=0; $i<count($path_widget); $i++) {
                $widget_name .= '\\'.$path_widget[$i];
            }
        } else {
            $widget_name = '\\frontend\\widgets'.$type.'\\'.mb_strtolower($matches[1]).'\\'.$matches[1];
        }
        $items['link'] = $link;
        if ($matches[2]) {
            $params = preg_split('/,/', $matches[2]);
            for ($i=0; $i<count($params); $i++) {
                $tmp = preg_split('/:/', $params[$i]);
                $items[trim($tmp[0])] = trim($tmp[1]);
            }
        }

        if (!class_exists($widget_name)) return Html::tag('div', '<strong>Class "'.$widget_name.'" is not exist!</strong>', ['class' => 'well text-muted']);

        return $widget_name::widget($items);
    }
}