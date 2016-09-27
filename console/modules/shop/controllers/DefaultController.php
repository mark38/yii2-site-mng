<?php

namespace app\modules\shop\controllers;

use Yii;
use yii\console\Controller;
use backend\modules\shop\models\Import;

/**
 * Default controller for the `shop` module
 */
class DefaultController extends Controller
{
    /**
     * Parser import.xml from ic
     * @param $import_xml
     * @return bool
     */
    public function actionImport($import_xml)
    {
        $start = microtime(true);

        $f = fopen(Yii::getAlias('@backend').Yii::$app->params['shop']['upload_dir'].'/upload.log', 'a');

        $model = new Import();
        $model->parser($import_xml);

        $time = microtime(true) - $start;
        fprintf($f, date('d.m.Y H:i')." Обработка из консоли файла Import({$import_xml}) выпонялась %.4F сек.\n", $time);
        fclose($f);

        return true;
    }
}
