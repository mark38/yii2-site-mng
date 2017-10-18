<?php

use yii\db\Migration;

class m171018_174231_insert_data_into_links extends Migration
{
    public function safeUp()
    {
        /**
         * Каталог
         * *************************************************************************
         */
        $this->insert('links', [
            'categories_id' => 1,
            'layouts_id' => 1,
            'views_id' => 1,
            'parent' => null,
            'url' => '/',
            'name' => 'index',
            'anchor' => 'Главная',
            'child_exist' => 0,
            'level' => 1,
            'seq' => 1,
            'title' => 'Ремонтная компания',
            'keywords' => false,
            'description' => false,
            'start' => 1,
            'priority' => '',
            'state' => 1,
            'content_nums' => 1,
            'css_class' => false,
            'icon' => false,
            'gallery_images_id' => null,
            'avg_rating' => '',
            'h1' => 'Продажа дверей'
        ]);

        $this->insert('links', [
            'categories_id' => 1,
            'layouts_id' => 1,
            'views_id' => 1,
            'parent' => null,
            'url' => '/flat-repair',
            'name' => 'flat-repair',
            'anchor' => 'Ремонт квартир',
            'child_exist' => 0,
            'level' => 1,
            'seq' => 1,
            'title' => 'Ремонт квартир',
            'keywords' => false,
            'description' => false,
            'start' => false,
            'priority' => 0.5,
            'state' => 1,
            'content_nums' => 1,
            'css_class' => false,
            'icon' => false,
            'gallery_images_id' => null,
            'avg_rating' => '',
            'h1' => 'Ремонт квартир'
        ]);
        $this->insert('links', [
            'categories_id' => 1,
            'layouts_id' => 1,
            'views_id' => 1,
            'parent' => null,
            'url' => '/flat-repair',
            'name' => 'flat-repair',
            'anchor' => 'Дизайн интерьеров',
            'child_exist' => 0,
            'level' => 1,
            'seq' => 1,
            'title' => 'Дизайн интерьеров',
            'keywords' => false,
            'description' => false,
            'start' => false,
            'priority' => 0.5,
            'state' => 1,
            'content_nums' => 1,
            'css_class' => false,
            'icon' => false,
            'gallery_images_id' => null,
            'avg_rating' => '',
            'h1' => 'Дизайн интерьеров'
        ]);

        /**
         * Меню сверху
         * ************************************************************************
         */
        $this->insert('links', [
            'categories_id' => 2,
            'layouts_id' => 1,
            'views_id' => 1,
            'parent' => null,
            'url' => '/contacts',
            'name' => 'contacts',
            'anchor' => 'Контакты',
            'child_exist' => 0,
            'level' => 1,
            'seq' => 1,
            'title' => 'Контакты',
            'keywords' => false,
            'description' => false,
            'start' => false,
            'priority' => '',
            'state' => 1,
            'content_nums' => 1,
            'css_class' => false,
            'icon' => false,
            'gallery_images_id' => null,
            'avg_rating' => '',
            'h1' => false
        ]);

        /**
         * Доп ссылки
         * ************************************************************************
         */
        $this->insert('links', [
            'categories_id' => 3,
            'layouts_id' => 1,
            'views_id' => 1,
            'parent' => null,
            'url' => '/search',
            'name' => 'search',
            'anchor' => 'Поиск',
            'child_exist' => 0,
            'level' => 1,
            'seq' => 1,
            'title' => 'Поиск',
            'keywords' => false,
            'description' => false,
            'start' => false,
            'priority' => '',
            'state' => 1,
            'content_nums' => 1,
            'css_class' => false,
            'icon' => false,
            'gallery_images_id' => null,
            'avg_rating' => '',
            'h1' => false
        ]);
    }

    public function safeDown()
    {
        /**
         * Каталог
         * *************************************************************************
         */
        $this->delete('links', [
            'name' => 'index'
        ]);
        $this->delete('links', [
            'name' => 'flat-repair'
        ]);

        /**
         * Меню сверху
         * ************************************************************************
         */
        $this->delete('links', [
            'name' => 'contacts'
        ]);

        /**
         * Доп ссылки
         * ************************************************************************
         */
        $this->delete('links', [
            'name' => 'search'
        ]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171018_174231_insert_data_into_links cannot be reverted.\n";

        return false;
    }
    */
}
