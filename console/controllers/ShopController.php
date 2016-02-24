<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use backend\models\shop\Import;
use backend\models\shop\Offers;

class ShopController extends Controller
{
    public function actionImport($import_xml)
    {
        $start = microtime(true);

        $f = fopen(Yii::getAlias('@backend').'/web/files/upload.log', 'a');

        $model = new Import();
        $model->parser($import_xml);

        $time = microtime(true) - $start;
        fprintf($f, "Обработка файла Import ({$import_xml}) выпонялась %.4F сек.\n", $time);
        fclose($f);

        return true;
    }

    public function actionOffers($offers_xml)
    {
        $start = microtime(true);

        $f = fopen(Yii::getAlias('@backend').'/web/files/upload.log', 'a');

        $model = new Offers();
        $model->parser($offers_xml);

        $time = microtime(true) - $start;
        fprintf($f, "Обработка файла Offers ({$offers_xml}) выпонялась %.4F сек.\n", $time);
        fclose($f);

        return true;
    }
}

