<?php

namespace app\modules\ipGeoBase\controllers;

use yii\console\Controller;
use himiklab\ipgeobase\IpGeoBase;
use yii\db\Migration;

/**
 * Default controller for the `ipGeoBase` module
 */
class DefaultController extends Controller
{
    /**
     * Update ip-geo-base data
     *
     * @throws \yii\base\Exception
     */
    public function actionUpdate()
    {
        $migrate = new Migration();
        $migrate->dropForeignKey('fk-geobase_contact-geobase_city_id', 'geobase_contact');
        $ipGeoBase = new IpGeoBase();
        $ipGeoBase->updateDB();
        $migrate->addForeignKey('fk-geobase_contact-geobase_city_id', 'geobase_contact', 'geobase_city_id', 'geobase_city', 'id', 'CASCADE', 'CASCADE');
    }
}
