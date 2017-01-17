<?php

namespace app\modules\news\models;

use Yii;
use common\models\news\News;
use common\models\news\NewsTypes;
use yii\helpers\ArrayHelper;
use common\models\main\Contents;

class NewsForm extends News
{
    public $newsTypes;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub

        $this->newsTypes = ArrayHelper::map(NewsTypes::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name');
    }

    public function afterFind()
    {
        parent::afterFind(); // TODO: Change the autogenerated stub

        $this->date = $this->date ? date('d.m.Y', strtotime($this->date)) : '';
        $this->date_range = $this->date_from && $this->date_to ? date('d.m.Y', strtotime($this->date_from)).' - '.date('d.m.Y', strtotime($this->date_to)) : null;
        $this->full_text = Contents::findOne(['links_id' => $this->links_id, 'seq' => 1])->text;
        $this->prev_text = Contents::findOne(['links_id' => $this->links_id, 'seq' => 2])->text;
    }
}