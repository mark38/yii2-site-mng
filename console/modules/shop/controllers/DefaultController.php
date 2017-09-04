<?php

namespace app\modules\shop\controllers;

use Yii;
use yii\console\Controller;
use backend\modules\shop\models\Import;
use common\models\main\Links;

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

    /**
     * @param string $file (format in utf8: anchor;url;name;amount)
     * @return bool
     */
    public function actionSynchronizationUrl($file)
    {
        $fp = fopen($file, 'rt');
        if ($fp) {
            while (!feof($fp)) {
                $strArr = preg_split("/;/", fgets($fp));
                if ($strArr[0]) {
                    echo $strArr[0]."\n";

                    $link = Links::find()
                        ->select(['id', 'anchor', 'url', 'count(id)'])
                        ->groupBy(['anchor'])
                        ->where(['anchor' => $strArr[0]])
                        ->having(['=', 'count(id)', '1'])
                        ->one();

                    if ($link && $strArr[1] && $strArr[2]) {
                        $link->url = $strArr[1];
                        $link->name = $strArr[2];
                        $link->update();

                        echo "Update ".$link->id."\n";
                    }
                }
            }
        }

        /*foreach ($srcLinks as $link) {
            echo $link->id.' | '.$link->url."\n";
        }*/

        echo "\n\n\n===============\n\n\n\n".count($links)."\n";

        return true;
    }
}
