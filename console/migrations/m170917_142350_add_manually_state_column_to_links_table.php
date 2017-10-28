<?php

use yii\db\Migration;
use common\models\main\Links;

/**
 * Handles adding manualy_state to table `links`.
 */
class m170917_142350_add_manually_state_column_to_links_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('links', 'manually_state', $this->boolean());

        $this->updateManuallyState();
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('links', 'manually_state');
    }

    public function updateManuallyState()
    {
        $links = Links::find()->all();
        if ($links) {
            /** @var Links $link */
            foreach ($links as $link) {
                $link->manually_state = $link->state;
                $link->update();
            }
        }
    }
}
