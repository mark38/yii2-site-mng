<?php

namespace app\modules\broadcast\controllers;

use common\models\broadcast\Broadcast;
use common\models\User;
use Yii;
use common\models\broadcast\BroadcastAddress;
use common\models\broadcast\BroadcastSend;
use yii\console\Controller;
use yii\helpers\Html;

/**
 * Sending mail
 * @package app\modules\broadcast\controllers
 */
class DefaultController extends Controller
{
    /**
     * @param $id BroadcastSend->id
     * @return bool
     */
    public function actionSend($id) {
        if (!$id) {
            return false;
        }

        $broadcast_send = BroadcastSend::findOne($id);
        $view = $broadcast_send->broadcast->broadcastLayout->layout_path;
        $title = $broadcast_send->broadcast->title;
        $broadcast_addresses = BroadcastAddress::find()->where(['broadcast_send_id' => $id, 'status' => 0])->all();

        $messages = [];
        /** @var $address BroadcastAddress */
        foreach ($broadcast_addresses as $address) {
            $email = $address->user_id ? $address->user->email : $address->email;
            $fio = $address->fio;
            $company = '';

            $mailer = Yii::$app->mailer->compose($view, [
                'content' => $this->handleContent($broadcast_send->broadcast, '', '')
            ])
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                ->setTo($email)
                ->setSubject($title);
            try {
                $mailer->send();
                $address->status = 1;
                $address->update();
            } catch (\Exception $e) {
                echo "ERROR\n";
            }
        } try {Yii::$app->mailer->sendMultiple($messages);} catch (Exception $e) {echo "Error\n";}

        $broadcast_send->status = 1;
        if ($broadcast_send->update()) {
            return true;
        }
        return false;
    }

    public function handleContent($broadcast, $fio, $company) {
        $patterns = array();
        $patterns[0] = '/{{if}}/';
        $patterns[1] = '/{{company}}/';
        $patterns[2] = '/{{current_date}}/';
        $patterns[3] = '/{{h1}}/';
        $replacements = array();
        $arr = preg_split('/\s/', $fio);
        $replacements[0] = isset($arr[1]) ? ' '.$arr[1].' '.$arr[0] : $arr[0];
        $replacements[1] = $company;
        $replacements[2] = date('d.m.Y',time());
        $replacements[3] = Html::tag('div', $broadcast->h1, ['style' => 'color:#333333;font-weight:900;font-size:18px;padding:4px 6px;text-align:center;']);
        $new_content = preg_replace($patterns, $replacements, $broadcast->content);
        return $new_content;
    }
}
