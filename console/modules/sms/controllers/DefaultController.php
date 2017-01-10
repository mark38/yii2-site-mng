<?php

namespace app\modules\sms\controllers;

use app\modules\sms\models\ServiceSmsSend;
use app\modules\sms\models\Upload;
use yii\console\Controller;
use common\models\sms\SmsServiceParams;

/**
 * Default controller for the `sms` module
 */
class DefaultController extends Controller
{
    /**
     * @param $sms_send_id
     */
    public function actionSend($sms_send_id)
    {
        $smsServiceParams = SmsServiceParams::find()->one();
        switch ($smsServiceParams->service_name) {
            case "smsru": ServiceSmsSend::sendSmsru($sms_send_id); break;
        }
    }

    public function actionUpload($excelFile)
    {
        $model = new Upload();
        return $model->uploadExcel($excelFile);
    }
}
