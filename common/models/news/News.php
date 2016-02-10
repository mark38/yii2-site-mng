<?php

namespace common\models\news;

use Yii;
use common\models\main\Links;

/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property integer $news_types_id
 * @property string $links_id
 * @property string $date
 *
 * @property NewsTypes $newsTypes
 * @property Links $links
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['news_types_id', 'links_id'], 'integer'],
            [['date'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'news_types_id' => 'Категория новости',
            'links_id' => 'Links ID',
            'date' => 'Дата новости',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewsType()
    {
        return $this->hasOne(NewsTypes::className(), ['id' => 'news_types_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLink()
    {
        return $this->hasOne(Links::className(), ['id' => 'links_id']);
    }
}
