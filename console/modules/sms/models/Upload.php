<?php

namespace app\modules\sms\models;

use common\models\sms\SmsContacts;
use Yii;
use yii\base\Model;

class Upload extends Model
{
    public function uploadExcel($excelFile)
    {
        if (!is_file($excelFile)) return false;

        SmsContacts::updateAll(['control' => 0]);

        $objPHPExcel = \PHPExcel_IOFactory::load($excelFile);
        foreach($objPHPExcel->getAllSheets() as $dataSheet) {
            foreach ($dataSheet->getRowIterator(2) as $row) {
                $phone = preg_replace('/[^0-9]/', '', trim($dataSheet->getCell('F'.$row->getRowIndex())));
                if (strlen($phone) == 11 && substr($phone, 0, 1) == 8) {
                    $phone = '7'.substr($phone, 1);
                }

                if (!ctype_digit($phone)) continue;

                $contact = SmsContacts::findOne(['phone' => $phone]);
                if (!$contact) {
                    $contact = new SmsContacts();
                    $contact->phone = $phone;
                }

                list($contact->surname, $contact->name, $contact->patronymic) = preg_split('/\s/', trim($dataSheet->getCell('A'.$row->getRowIndex())));

                $unixDate = (trim($dataSheet->getCell('B'.$row->getRowIndex())) - 25569) * 86400;
                $contact->dob = gmdate("Y-m-d", $unixDate);

                $contact->card_number = trim($dataSheet->getCell('C'.$row->getRowIndex()));

                $unixDate = (trim($dataSheet->getCell('D'.$row->getRowIndex())) - 25569) * 86400;
                $contact->date_registration = gmdate("Y-m-d", $unixDate);

                $contact->state = trim($dataSheet->getCell('E'.$row->getRowIndex())) == 'да' ? 1 : 0;

                $contact->email = trim($dataSheet->getCell('G'.$row->getRowIndex()));

                $gender = trim($dataSheet->getCell('H'.$row->getRowIndex()));
                switch ($gender) {
                    case "м": $contact->gender = 1; break;
                    case "ж": $contact->gender = 2; break;
                    default: $contact->gender = 0;
                }

                $contact->delivery_address = trim($dataSheet->getCell('I'.$row->getRowIndex()));
                $contact->control = 1;

                if ($contact->validate()) {
                    $contact->save();
                } else {
                    var_dump($contact);
                }
            }
        }
    }
}