<?php

namespace app\modules\sms\models;

use Yii;
use yii\base\Module;
use common\models\sms\SmsSend;
use common\models\sms\SmsSendContacts;
use common\models\sms\SmsServiceParams;

class ServiceSmsSend extends Module
{
    public function sendSmsru($sms_send_id)
    {
        $smsServiceParam = SmsServiceParams::findOne(1);
        $smsSend = SmsSend::findOne($sms_send_id);

        if (!$smsSend) return false;

        /** @var SmsSendContacts $smsSendContact */
        foreach (SmsSendContacts::find()->where(['sms_send_id' => $smsSend->id, 'status' => false])->all() as $smsSendContact) {
            $content = self::handleContent($smsSend->smsContent->content, $smsSendContact->smsContact);

            $client = new \Zelenin\SmsRu\Api(new \Zelenin\SmsRu\Auth\ApiIdAuth($smsServiceParam->smsru_api_id));

            $sms = new \Zelenin\SmsRu\Entity\Sms($smsSendContact->smsContact->phone, $content);
            $result = $client->smsSend($sms);

            $smsSendContact->status = 1;
            $smsSendContact->smsru_id = $result->ids[0];
            $smsSendContact->smsru_result_code = $result->code;
            $smsSendContact->update();
        }

        $smsSend->status = 1;
        $smsSend->update();

        return true;
    }

    public function sendSmsruDef($sms_send_id)
    {
        $smsServiceParam = SmsServiceParams::findOne(1);
        $smsSend = SmsSend::findOne($sms_send_id);

        if (!$smsSend) return false;

        /** @var SmsSendContacts $smsSendContact */
        foreach (SmsSendContacts::find()->where(['sms_send_id' => $smsSend->id, 'status' => false])->all() as $smsSendContact) {
            $content = self::handleContent($smsSend->smsContent->content, $smsSendContact->smsContact);

            $ch = curl_init("http://sms.ru/sms/send");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array(
                "api_id" => $smsServiceParam->smsru_api_id,
                "from" => $smsServiceParam->smsru_from,
                "to" => $smsSendContact->smsContact->phone,
                "text" => $content,
                "test" => 1,
            ));
            $body = curl_exec($ch);
            curl_close($ch);

            print_r($body);

//            $smsSendContact->status = 1;
//            $smsSendContact->update();
        }

//        $smsSend->status = 1;
//        $smsSend->update();
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