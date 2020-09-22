<?php
namespace frontend\widgets\nav;

use Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

class Top extends Widget
{
    public $link;
    public $links;
    public $breadcrumbs;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub

        if (empty($this->link)) {
            throw new InvalidConfigException("The 'link' property has not been set.");
        }

        if (empty($this->links)) {
            throw new InvalidConfigException("The 'links' property has not been set.");
        }
    }

    public function run()
    {
        parent::run(); // TODO: Change the autogenerated stub
        $asset = NavAsset::register($this->view);
        $breadcrumbsId = ArrayHelper::index($this->breadcrumbs, 'id');

        echo $this->render('topNav', ['links' => $this->links, 'breadcrumbsId' => $breadcrumbsId]);
    }
}