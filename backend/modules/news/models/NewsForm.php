<?php

namespace app\modules\news\models;

use Yii;
use common\models\news\News;
use common\models\news\NewsTypes;
use yii\helpers\ArrayHelper;

class NewsForm extends News
{
    public $newsTypes;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub

        $this->newsTypes = ArrayHelper::map(NewsTypes::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name');
    }
}