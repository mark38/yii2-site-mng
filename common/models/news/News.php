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
 * @property string $url
 * @property string $date
 * @property string $date_start
 * @property string $date_finish
 *
 * @property NewsTypes $newsTypes
 * @property Links $links
 */
class News extends \yii\db\ActiveRecord
{
    public $prev_text;
    public $full_text;
    public $date_range;

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
            [['url', 'prev_text', 'full_text'], 'string'],
            [['date', 'date_start', 'date_finish', 'date_range'], 'safe']
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
            'url' => 'Адрес внешней ссылки',
            'date' => 'Дата новости',
            'prev_text' => 'Предварительный текст нововсти',
            'full_text' => 'Полный текст новости',
            'date_range' => 'Период публикации'
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

    public function beforeSave($insert)
    {
        $this->date = $this->date ? date('Y-m-d', strtotime($this->date)) : null;
        if ($this->date_range) {
            preg_match('/(\d+\.\d+\.\d+) - (\d+\.\d+\.\d+)/', $this->date_range, $match);
            $this->date_start = isset($match[1]) ? date('Y-m-d', strtotime($match[1])) : null;
            $this->date_finish = isset($match[2]) ? date('Y-m-d', strtotime($match[2])) : null;
        }

        return true;
    }
}
