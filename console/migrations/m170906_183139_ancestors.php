<?php

use yii\db\Migration;
use common\models\main\Links;
use common\models\main\Ancestors;

class m170906_183139_ancestors extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('ancestors', [
            'id' => $this->primaryKey(),
            'links_id' => $this->integer(),
            'ancestor_links_id' => $this->integer(),
        ]);

        $this->addForeignKey('fk-ancestors-links_id', 'ancestors', 'links_id', 'links', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-ancestors-ancestor_links_id', 'ancestors', 'ancestor_links_id', 'links', 'id', 'CASCADE', 'CASCADE');

        $this->refresh();
    }

    public function safeDown()
    {
        $this->dropTable('ancestors');
    }

    public function refresh()
    {
        $links = Links::find()->all();

        if (!$links) {
            return "Links is empty\n";
        }

        $model = new Ancestors();

        /** @var Links $link */
        foreach ($links as $link) {
            echo 'Insert ancestor: '.$link->id."\n";
            $model->updateAncestor($link);
        }

        return true;
    }
}
