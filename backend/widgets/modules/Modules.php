<?php

namespace backend\widgets\modules;

use Yii;
use yii\base\Widget;
use yii\bootstrap\Nav;

class Modules extends Widget
{
    public $active_module = false;
    public function init()
    {

    }

    public function run()
    {
        $items[] = [
            'label' => '<span class="sr-only">Вид навигации</span>',
            'url' => '#',
            'linkOptions' => [
                'class' => 'sidebar-toggle hidden-xs',
                'data-toggle' => 'offcanvas',
                'role' => 'button'
            ]
        ];
        $items[] = [
            'label' => 'Карта сайта',
            'url' => ['/map/index'],
            'active' => $this->active_module && $this->active_module->id == 'map' ? true : false,
        ];

        $modules = \common\models\main\Modules::find()->where(['visible' => 1])->orderBy(['seq' => SORT_ASC])->all();
        if ($modules) {
            $model_items = array();
            foreach ($modules as $model) {
                $model_items[] = [
                    'label' => $model->name,
                    'url' => [$model->url],
                ];
            }
            $items[] = [
                'label' => 'Модули',
                'items' => $model_items
            ];
        }

        echo Nav::widget([
            'items' => $items,
            'options' => ['class' => 'nav navbar-nav'],
            'encodeLabels' => false,
        ]);
    }
}