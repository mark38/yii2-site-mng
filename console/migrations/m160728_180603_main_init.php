<?php

use yii\db\Migration;
use common\models\User;

class m160728_180603_main_init extends Migration
{
    public function up()
    {
        $user = new User();
        $user->username = 'admin';
        $user->email = Yii::$app->params['adminEmail'];
        $user->setPassword('admin');
        $user->generateAuthKey();
        $user->save();

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('categories', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'comment' => $this->string(255),
            'seq' => $this->integer(),
            'visible' => $this->boolean()
        ], $tableOptions);

        $this->insert('categories', [
            'name' => 'main',
            'comment' => 'Main menu',
            'seq' => 1,
            'visible' => 1
        ]);

        $this->createTable('layouts', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->unique(),
            'comment' => $this->string(255)->unique(),
            'seq' => $this->integer()
        ], $tableOptions);
        $this->insert('layouts', [
            'name' => 'main',
            'comment' => 'Main layout',
            'seq' => 1
        ]);

        $this->createTable('views', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->unique(),
            'comment' => $this->string(255),
            'seq' => $this->integer()
        ], $tableOptions);
        $this->insert('views', [
            'name' => '/site/index',
            'comment' => 'Main site view',
            'seq' => 1
        ]);

        $this->createTable('sessions', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)
        ], $tableOptions);

        $this->createTable('links', [
            'id' => $this->primaryKey(),
            'categories_id' => $this->integer()->defaultValue(1),
            'layouts_id' => $this->integer()->defaultValue(1),
            'views_id' => $this->integer()->defaultValue(1),
            'parent' => $this->integer(),
            'url' => $this->string(255)->unique(),
            'name' => $this->string(255),
            'anchor' => $this->string(255),
            'child_exist' => $this->boolean(),
            'level' => $this->integer(),
            'seq' => $this->integer(),
            'title' => $this->string(1024),
            'keywords' => $this->string(1024),
            'description' => $this->string(1024),
            'start' => $this->boolean()->defaultValue(0),
            'priority' => $this->decimal(2,1)->defaultValue(0.5),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'state' => $this->boolean(),
            'content_nums' => $this->integer()->defaultValue(1),
            'css_class' => $this->string(255),
            'icon' => $this->string(255),
        ], $tableOptions);

        $this->addForeignKey('fk-links-categories_id', 'links', 'categories_id', 'categories', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-links-layouts_id', 'links', 'layouts_id', 'layouts', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-links-views_id', 'links', 'views_id', 'views', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-links-parent', 'links', 'parent', 'links', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('contents', [
            'id' => $this->primaryKey(),
            'links_id' => $this->integer(),
            'parent' => $this->integer(),
            'css_class' => $this->string(255),
            'text' => $this->text(),
            'seq' => $this->integer()
        ], $tableOptions);

        $this->addForeignKey('fk-contents-links_id', 'contents', 'links_id', 'links', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-contents-parent', 'contents', 'parent', 'contents', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('redirects', [
            'id' => $this->primaryKey(),
            'links_id' => $this->integer(),
            'url' => $this->integer(),
            'code' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('fk-redirects-links_id', 'redirects', 'links_id', 'links', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('search', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
        ], $tableOptions);

        $this->createTable('gallery_types', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->unique(),
            'comment' => $this->string(255),
            'destination' => $this->string(255),
            'small_width' => $this->integer()->defaultValue(0),
            'small_height' => $this->integer()->defaultValue(0),
            'large_width' => $this->integer()->defaultValue(0),
            'large_height' => $this->integer()->defaultValue(0),
            'quality' => $this->integer()->defaultValue(80),
            'visible' => $this->boolean()->defaultValue(1)
        ], $tableOptions);

        $this->createTable('gallery_groups', [
            'id' => $this->primaryKey(),
            'gallery_types_id' => $this->integer(),
            'gallery_images_id' => $this->integer(),
            'name' => $this->string(255)
        ], $tableOptions);

        $this->createTable('gallery_images', [
            'id' => $this->primaryKey(),
            'gallery_groups_id' => $this->integer(),
            'small' => $this->string(255),
            'large' => $this->string(255),
            'name' => $this->string(255),
            'alt' => $this->string(255),
            'url' => $this->string(255),
            'seq' => $this->integer()
        ], $tableOptions);

        $this->addForeignKey('fk-gallery_images-gallery_groups_id', 'gallery_images', 'gallery_groups_id', 'gallery_groups', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-gallery_groups-gallery_types_id', 'gallery_groups', 'gallery_types_id', 'gallery_types', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-gallery_groups-gallery_images_id', 'gallery_groups', 'gallery_images_id', 'gallery_images', 'id', 'CASCADE', 'CASCADE');

        $this->insert('gallery_types', [
            'name' => 'links',
            'comment' => 'Link images',
            'destination' => '/assets/static/image',
            'visible' => 0
        ]);
        $this->insert('gallery_types', [
            'name' => 'news',
            'comment' => 'News',
            'destination' => '/assets/static/image',
            'small_width' => 350,
            'small_height' => 300,
            'visible' => 0,
        ]);
        $this->insert('gallery_types', [
            'name' => 'gallery',
            'comment' => 'Photo gallery',
            'destination' => '/assets/static/image/gallery',
            'small_width' => 280,
            'small_height' => 280,
            'large_width' => 800
        ]);
        $this->insert('gallery_groups', [
            'gallery_types_id' => 1,
            'name' => 'Links'
        ]);


        $this->addColumn('links', 'gallery_images_id', $this->integer());
        $this->addForeignKey('fk-links-gallery_images_id', 'links', 'gallery_images_id', 'gallery_images', 'id', 'SET NULL', 'CASCADE');

        $this->createTable('news_types', [
            'id' => $this->primaryKey(),
            'categories_id' => $this->integer(),
            'links_id' => $this->integer(),
            'name' => $this->string(255),
            'gallery_types_id' => $this->integer(),
            'gallery_groups_id' => $this->integer(),
        ], $tableOptions);

        $this->createTable('news', [
            'id' => $this->primaryKey(),
            'news_types_id' => $this->integer(),
            'links_id' => $this->integer(),
            'url' => $this->string(255),
            'date' => $this->date(),
            'date_from' => $this->date(),
            'date_to' => $this->date()
        ], $tableOptions);

        $this->addForeignKey('fk-news_types-categories_id', 'news_types', 'categories_id', 'categories', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-news_types-links_id', 'news_types', 'links_id', 'links', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-news-news_types_id', 'news', 'news_types_id', 'news_types', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-news-links_id', 'news', 'links_id', 'links', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('modules', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'url' => $this->string(255),
            'visible' => $this->boolean()->defaultValue(1),
            'icon' => $this->string(255),
            'seq' => $this->integer()
        ]);

        $this->insert('modules', [
            'name' => 'Новости',
            'url' => '/news/index',
            'visible' => true,
            'icon' => 'newspaper-o'
        ]);

        $this->insert('modules', [
            'name' => 'Фотогалерея',
            'url' => '/gallery/index',
            'visible' => true,
            'icon' => 'file-image-o'
        ]);
    }

    public function down()
    {
        $this->dropTable('modules');
        $this->dropTable('news');
        $this->dropTable('news_types');
        $this->dropForeignKey('fk-links-gallery_images_id', 'links');
        $this->dropForeignKey('fk-gallery_groups-gallery_images_id', 'gallery_groups');
        $this->dropTable('gallery_images');
        $this->dropTable('gallery_groups');
        $this->dropTable('gallery_types');
        $this->dropTable('search');
        $this->dropTable('redirects');
        $this->dropTable('contents');
        $this->dropTable('links');
        $this->dropTable('sessions');
        $this->dropTable('views');
        $this->dropTable('layouts');
        $this->dropTable('categories');
        $this->delete('user');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
