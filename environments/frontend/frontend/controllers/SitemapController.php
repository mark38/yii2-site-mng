<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\main\Links;

class SitemapController extends Controller
{
    public function actionIndex()
    {
        $links = Links::find()->where(['state' => 1])->all();

        echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        /** @var Links $link */
        foreach ($links as $link) {
            if (!$link->updated_at || !$link->priority) continue;

            echo '<url>'.
                '<loc>'.Yii::$app->params['hostname'].$link->url.'</loc>' .
                '<lastmod>'.date('Y-m-d', ($link->updated_at ? $link->updated_at : date())).'</lastmod>' .
                '<changefreq>daily</changefreq>' .
                '<priority>'.$link->priority.'</priority>' .
                '</url>'.PHP_EOL;
        }

        echo '</urlset>';

        Yii::$app->end();
    }
}