<?php

namespace app\modules\certificates\controllers;

use console\modules\certificates\models\LoadFromCertificate;
use yii\console\Controller;

/**
 * Handling certificates
 * @package app\modules\certificates\controllers
 */
class LoadFromCertificateController extends Controller
{
    public function actionLoadFromFile($file, $number=null)
    {
        (new LoadFromCertificate())->loadFromFile($file);
    }
}
