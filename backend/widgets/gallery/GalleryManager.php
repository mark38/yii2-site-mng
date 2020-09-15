<?php
namespace backend\widgets\gallery;

use Yii;
use yii\helpers\Json;
use yii\widgets\InputWidget;
use yii\base\InvalidConfigException;
use yii\helpers\Html;

class GalleryManager extends InputWidget
{
    public $group = true;
    public $gallery_groups_id = false;
    public $pluginOptions = [];
    public $options = [];
    private $type;

    public function init()
    {
        if (!$this->group && !$this->gallery_groups_id) {
            throw new InvalidConfigException("The 'gallery_groups_id' property has not been set.");
        }

        if (empty($this->pluginOptions['type'])) {
            throw new InvalidConfigException("The 'pluginOptions[\"type\"]' property has not been set.");
        }

        if (empty($this->pluginOptions['apiUrl'])) {
            throw new InvalidConfigException("The 'pluginOptions[\"apiUrl\"]' property has not been set.");
        }

        if (empty($this->pluginOptions['webRoute'])) {
            throw new InvalidConfigException("The 'pluginOptions[\"webRoute\"]' property has not been set.");
        }

        $this->type = (new Gallery())->getType($this->pluginOptions['type']);
        if (!$this->type) {
            throw new InvalidConfigException("The 'type' is absent in database.");
        }
    }

    public function run()
    {
        echo $form = Html::tag('div', Html::activeHiddenInput($this->model, $this->attribute, $this->options), ['id' => 'input-'.$this->getId()]);

        $this->gallery_groups_id = $this->group ? (isset($this->model[$this->attribute]) ? $this->model[$this->attribute] : false) : $this->gallery_groups_id;
        $gallery_images_id = !$this->group ? (isset($this->model[$this->attribute]) ? $this->model[$this->attribute] : false) : false;

        $opts = Json::encode([
            'group' => $this->group ? 1 : 0,
            'url' => [$this->pluginOptions['apiUrl']],
            'route' => [$this->pluginOptions['webRoute']],
            'gallery_types_id' => $this->type['id'],
            'gallery_groups_id' => $this->gallery_groups_id,
            'gallery_images_id' => $gallery_images_id,
            'widget_id' => $this->getId(),
        ]);

        $view = $this->getView();
        GalleryManagerAsset::register($view);
        $view->registerJs("$('#content-{$this->id}').galleryManager({$opts});");

        $this->options['id'] = 'content-'.$this->id.(isset($this->options['id']) ? ' '.$this->options['id'] : '');
        $this->options['class'] = 'gallery-manager'.(isset($this->options['class']) ? ' '.$this->options['class'] : '');

        echo Html::beginTag('div', $this->options);
        echo $this->render('galleryManager', [
            'group' => $this->group,
            'gallery_groups_id' => $this->gallery_groups_id,
            'gallery_images_id' => $gallery_images_id,
        ]);
        if ($this->group) echo Html::tag('div', 'Основное изображение', ['class' => 'default-image-comment']);
        echo Html::endTag('div');
    }
}