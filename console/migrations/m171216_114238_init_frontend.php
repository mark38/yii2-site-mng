<?php

use yii\db\Migration;

/**
 * Class m171216_114238_init_frontend
 */
class m171216_114238_init_frontend extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('{{categories}}', [
            'name' => 'top',
            'comment' => 'Меню сверху',
            'seq' => 2,
            'visible' => 1
        ]);

        $this->insert('{{categories}}', [
            'name' => 'other',
            'comment' => 'Дополнительные ссылки',
            'seq' => 3,
            'visible' => 1
        ]);

        $this->insert('{{views}}', [
            'name' => '/site/main',
            'comment' => 'Main site view',
            'seq' => 2
        ]);

        $this->insert('{{layouts}}', [
            'name' => 'index',
            'comment' => 'Index layout',
            'seq' => 2
        ]);

        $this->insert('{{layouts}}', [
            'name' => 'shopGroup',
            'comment' => 'Shop group layout',
            'seq' => 3
        ]);

        $this->insert('{{links}}', [
            'categories_id' => 1,
            'layouts_id' => 2,
            'views_id' => 1,
            'parent' => null,
            'url' => '/',
            'name' => 'index',
            'anchor' => 'Главная',
            'child_exist' => 0,
            'level' => 1,
            'seq' => 1,
            'title' => 'Шаблон фронтенда',
            'start' => 1,
            'state' => 1,
            'content_nums' => 1,
            'h1' => 'Шаблон фронтенда',
            'manually_state' => 1,
        ]);

        $this->insert('{{links}}', [
            'categories_id' => 2,
            'layouts_id' => 1,
            'views_id' => 2,
            'parent' => null,
            'url' => '/contacts',
            'name' => 'contacts',
            'anchor' => 'Контакты',
            'child_exist' => 0,
            'level' => 1,
            'seq' => 1,
            'title' => 'Контакты',
            'start' => 0,
            'state' => 1,
            'content_nums' => 1,
            'h1' => 'Контакты',
            'manually_state' => 1,
        ]);

        $this->insert('{{links}}', [
            'categories_id' => 3,
            'layouts_id' => 1,
            'views_id' => 2,
            'parent' => null,
            'url' => '/404',
            'name' => '404',
            'anchor' => '404 - нет такой страницы',
            'child_exist' => 0,
            'level' => 1,
            'seq' => 1,
            'title' => '404 - нет такой страницы',
            'start' => 0,
            'state' => 1,
            'content_nums' => 1,
            'h1' => '404 - нет такой страницы',
            'manually_state' => 1,
        ]);

        $this->insert('{{links}}', [
            'categories_id' => 1,
            'layouts_id' => 2,
            'views_id' => 2,
            'parent' => null,
            'url' => '/test',
            'name' => 'test',
            'anchor' => 'Тестовая страница',
            'child_exist' => 0,
            'level' => 1,
            'seq' => 2,
            'title' => 'Тестовая страница',
            'start' => 0,
            'state' => 1,
            'content_nums' => 1,
            'h1' => 'Тестовая страница',
            'manually_state' => 1,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->delete('{{categories}}', [
            'name' => 'top'
        ]);

        $this->delete('{{categories}}', [
            'name' => 'other'
        ]);

        $this->delete('{{views}}', [
            'name' => '/site/main'
        ]);

        $this->delete('{{layouts}}', [
            'name' => 'index'
        ]);

        $this->delete('{{layouts}}', [
            'name' => 'shopGroup'
        ]);

        $this->delete('{{links}}', [
            'url' => '/'
        ]);

        $this->delete('{{links}}', [
            'url' => '/contacts'
        ]);

        $this->delete('{{links}}', [
            'url' => '/404'
        ]);

        $this->delete('{{links}}', [
            'url' => '/test'
        ]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171216_114238_init_frontend cannot be reverted.\n";

        return false;
    }
    */
}
