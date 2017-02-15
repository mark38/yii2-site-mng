<?php

namespace common\models\auctionmb;

use Yii;

/**
 * This is the model class for table "auctionmb_bets".
 *
 * @property integer $id
 * @property integer $auctionmb_users_id
 * @property integer $auctionmb_lots_id
 * @property integer $created_at
 *
 * @property AuctionmbLots $auctionmbLots
 * @property AuctionmbUsers $auctionmbUsers
 */
class AuctionmbBets extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auctionmb_bets';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['auctionmb_users_id', 'auctionmb_lots_id', 'created_at'], 'integer'],
            [['auctionmb_lots_id'], 'exist', 'skipOnError' => true, 'targetClass' => AuctionmbLots::className(), 'targetAttribute' => ['auctionmb_lots_id' => 'id']],
            [['auctionmb_users_id'], 'exist', 'skipOnError' => true, 'targetClass' => AuctionmbUsers::className(), 'targetAttribute' => ['auctionmb_users_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'auctionmb_users_id' => 'Пользователь',
            'auctionmb_lots_id' => 'Лот',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuctionmbLots()
    {
        return $this->hasOne(AuctionmbLots::className(), ['id' => 'auctionmb_lots_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuctionmbUsers()
    {
        return $this->hasOne(AuctionmbUsers::className(), ['id' => 'auctionmb_users_id']);
    }
}
