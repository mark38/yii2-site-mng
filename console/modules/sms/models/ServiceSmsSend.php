<?php

namespace app\modules\sms\models;

use common\models\sms\SmsSendContacts;
use Yii;
use yii\base\Module;
use common\models\sms\SmsSend;

class ServiceSmsSend extends Module
{
    public function sendSmsru($sms_send_id)
    {
        $smsSend = SmsSend::findOne($sms_send_id);

        if (!$smsSend) return false;

        /** @var SmsSendContacts $smsSendContact */
        foreach (SmsSendContacts::find()->where(['sms_send_id' => $smsSend->id, 'status' => false])->all() as $smsSendContact) {
            $content = self::handleContent($smsSend->smsContent->content, $smsSendContact->smsContact);
            echo $content."\n";

            $smsSendContact->status = 1;
            $smsSendContact->update();
        }

        $smsSend->status = 1;
        $smsSend->update();
    }

    public function handleContent($content, $smsContact)
    {
        $patterns = array();
        $patterns[0] = '/{{ИФ}}/';
        $patterns[1] = '/{{ИО}}/';
        $patterns[2] = '/{{И}}/';
        $patterns[3] = '/{{Уважаемый}}/';

        $replacements = array();
        $replacements[0] = $smsContact->name.' '.$smsContact->surname;
        $replacements[1] = $smsContact->name.' '.$smsContact->patronymic;
        $replacements[2] = $smsContact->name;

        $appeal = '';
        if ($smsContact->male) {
            $appeal = 'Уважаемый';
        } else {
            $appeal = 'Уважаемая';
        }
        $replacements[3] = $appeal;

        $newContent = preg_replace($patterns, $replacements, $content);

        return $newContent;
    }
}