<?php

namespace app\modules\sms\models;

use common\models\sms\SmsContacts;
use Yii;
use yii\base\Model;

class SmsModel extends Model
{
    public function renderContactsXlsx()
    {
        $objPHPExcel = new \PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->setCellValueByColumnAndRow(0, 1, 'Номер телефона');
        $sheet->setCellValueByColumnAndRow(1, 1, 'Фамилия');
        $sheet->setCellValueByColumnAndRow(2, 1, 'Имя');
        $sheet->setCellValueByColumnAndRow(3, 1, 'Отчество');
        $sheet->setCellValueByColumnAndRow(4, 1, 'Пол (м|ж)');
        $sheet->setCellValueByColumnAndRow(5, 1, 'Состояние (1-активно|0-выключено)');

        $contacts = SmsContacts::find()->where(['control' => true])->all();
        if ($contacts) {
            /**
             * @var integer $i
             * @var SmsContacts $contact
             */
            foreach ($contacts as $i => $contact) {
                $row = $i + 2;
                $gender = '';
                if ($contact->male) {
                    $gender = 'м';
                } elseif ($contact->female) {
                    $gender = 'ж';
                }
                $sheet->setCellValueByColumnAndRow(0, $row, $contact->phone);
                $sheet->setCellValueByColumnAndRow(1, $row, $contact->surname);
                $sheet->setCellValueByColumnAndRow(2, $row, $contact->name);
                $sheet->setCellValueByColumnAndRow(3, $row, $contact->patronymic);
                $sheet->setCellValueByColumnAndRow(4, $row, $gender);
                $sheet->setCellValueByColumnAndRow(5, $row, ($contact->state ? '1' : '0'));
            }
        }

        return $objPHPExcel;
    }
}