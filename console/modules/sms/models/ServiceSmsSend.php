<?php

namespace app\modules\sms\models;

use Yii;
use yii\base\Module;
use common\models\sms\SmsSend;

class ServiceSmsSend extends Module
{
    public function sendSmsru($sms_send_id)
    {
        $smsSend = SmsSend::findOne($sms_send_id);
        $smsSend->status = 1;
        $smsSend->update();
    }
}