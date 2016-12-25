<?php

use yii\db\Migration;

/**
 * Handles adding views_id to table `news_types`.
 */
class m161224_230508_add_views_id_column_to_news_types_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('news_types', 'views_id', $this->integer());
        $this->addForeignKey('fk-news_types-views_id', 'news_types', 'views_id', 'views', 'id', 'SET NULL', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk-news_types-views_id', 'news_types');
        $this->dropColumn('news_types', 'views_id');
    }
}
