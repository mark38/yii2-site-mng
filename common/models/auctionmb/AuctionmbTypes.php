<?php

namespace common\models\auctionmb;

use common\models\main\Links;
use Yii;

/**
 * This is the model class for table "auctionmb_types".
 *
 * @property integer $id
 * @property integer $links_id
 * @property integer $bets
 *
 * @property AuctionmbLots[] $auctionmbLots
 * @property Links $links
 */
class AuctionmbTypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auctionmb_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['links_id', 'bets'], 'integer'],
            [['links_id'], 'exist', 'skipOnError' => true, 'targetClass' => Links::className(), 'targetAttribute' => ['links_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'links_id' => 'Links ID',
            'bets' => 'Количество ставок',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuctionmbLots()
    {
        return $this->hasMany(AuctionmbLots::className(), ['auctionmb_types_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinks()
    {
        return $this->hasOne(Links::className(), ['id' => 'links_id']);
    }
}
