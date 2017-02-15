<?php

namespace common\models\auctionmb;

use common\models\User;
use Yii;

/**
 * This is the model class for table "auctionmb_users".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $surname
 * @property string $patronymic
 *
 * @property AuctionmbBets[] $auctionmbBets
 * @property AuctionmbLots[] $auctionmbLots
 * @property User $user
 */
class AuctionmbUsers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auctionmb_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['name', 'surname', 'patronymic'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь системы',
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'patronymic' => 'Отчество',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuctionmbBets()
    {
        return $this->hasMany(AuctionmbBets::className(), ['auctionmb_users_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuctionmbLots()
    {
        return $this->hasMany(AuctionmbLots::className(), ['auctionmb_users_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
