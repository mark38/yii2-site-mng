<?php

namespace common\models\auctionmb;

use Yii;

/**
 * This is the model class for table "auctionmb_lots".
 *
 * @property integer $id
 * @property integer $auctionmb_types_id
 * @property integer $auctionmb_users_id
 * @property integer $state
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property AuctionmbBets[] $auctionmbBets
 * @property AuctionmbUsers $auctionmbUsers
 * @property AuctionmbTypes $auctionmbTypes
 */
class AuctionmbLots extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auctionmb_lots';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['auctionmb_types_id', 'auctionmb_users_id', 'state', 'created_at', 'updated_at'], 'integer'],
            [['auctionmb_users_id'], 'exist', 'skipOnError' => true, 'targetClass' => AuctionmbUsers::className(), 'targetAttribute' => ['auctionmb_users_id' => 'id']],
            [['auctionmb_types_id'], 'exist', 'skipOnError' => true, 'targetClass' => AuctionmbTypes::className(), 'targetAttribute' => ['auctionmb_types_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'auctionmb_types_id' => 'Тип',
            'auctionmb_users_id' => 'Пользователь',
            'state' => 'Состояние',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuctionmbBets()
    {
        return $this->hasMany(AuctionmbBets::className(), ['auctionmb_lots_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuctionmbUsers()
    {
        return $this->hasOne(AuctionmbUsers::className(), ['id' => 'auctionmb_users_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuctionmbTypes()
    {
        return $this->hasOne(AuctionmbTypes::className(), ['id' => 'auctionmb_types_id']);
    }
}
