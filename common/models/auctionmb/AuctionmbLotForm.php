<?php

namespace common\models\auctionmb;

use Yii;

class AuctionmbLotForm extends AuctionmbLots
{
    public $text;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['text'], 'string'],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'text' => 'Описание'
        ]);
    }
}