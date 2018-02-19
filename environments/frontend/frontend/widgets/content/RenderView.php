<?php
namespace frontend\widgets\content;

use Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;

class RenderView extends Widget
{
    public $name;
    public $link;

    public function init()
    {
        if (empty($this->name)) {
            throw new InvalidConfigException("The 'name' property has not been set.");
        }
    }

    public function run()
    {
        echo $this->render($this->name);
    }
}