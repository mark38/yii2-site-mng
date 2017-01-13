<?php

namespace app\modules\sms\controllers;

use app\modules\sms\models\RenderSendForm;
use app\modules\sms\models\SmsContentForm;
use common\helpers\RunConsole;
use common\models\sms\SmsContent;
use common\models\sms\SmsSend;
use common\models\sms\SmsSendContacts;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Response;
use common\models\sms\SmsContacts;
use yii\bootstrap\Html;
use app\modules\sms\models\SmsModel;
use app\modules\sms\models\UploadFileForm;
use yii\web\UploadedFile;

/**
 * Default controller for the `sms` module
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['error', 'smsru-result'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index', 'content', 'render-send', 'send', 'send-result', 'contacts', 'contact', 'contact-mng', 'contacts-upload', 'contacts-load'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub

        ini_set('memory_limit', '512M');
        ini_set('max_input_time', '102000');
        ini_set('upload_max_filesize', '104857600');
        ini_set('post_max_size', '104857600');
        ini_set('max_execution_time', '102000');
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $contents = SmsContent::find()->orderBy(['created_at' => SORT_ASC])->all();

        return $this->render('contents', ['contents' => $contents]);
    }

    public function actionContent($id = null)
    {
        $content = $id ? SmsContentForm::findOne($id) : new SmsContent();
        $contactsCount = SmsContacts::find()->where(['control' => true, 'state' => true])->count();

        if ($content->load(Yii::$app->request->post()) && $content->save()) {
            Yii::$app->session->setFlash('Изменения приняты');
            return $this->redirect(['content', 'id' => $content->id]);
        }

        return $this->render('contentForm', ['content' => $content, 'contactsCount' => $contactsCount]);
    }

    public function actionRenderSend($content_id = null)
    {
        if (!$content_id) {
            Yii::$app->session->setFlash('error', 'Не выбран текст сообщения');
            return $this->redirect('index');
        }

        $smsContent = SmsContent::findOne($content_id);
        $smsSend = SmsSend::find()->where(['sms_content_id' => $content_id, 'status' => false])->orderBy(['created_at' => SORT_DESC])->one();
        $sendContacts = SmsSendContacts::find()->where(['sms_send_id' => $smsSend->id])->all();
        $model = new RenderSendForm();
        $model->sms_send_id = $smsSend->id;

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            return $this->redirect(['send', 'send_id' => $smsSend->id]);
        } else {
            $model->contact_ids = ArrayHelper::getColumn($sendContacts, 'id');
        }

        return $this->render('renderSend', ['smsContent' => $smsContent, 'sendContacts' => $sendContacts, 'model' => $model]);
    }

    public function actionSend($send_id)
    {
        $runConsole = new RunConsole(['file' => preg_replace('@(frontend/|frontend|frontend\\))@', '', Yii::getAlias('@app')) . '/../yii']);
        $runConsole->run("sms/default/send " . $send_id);

        return $this->redirect(['send-result', 'send_id' => $send_id]);
    }

    public function actionSendResult($send_id)
    {
        $send = SmsSend::findOne($send_id);
        $sendContacts = SmsSendContacts::find()->where(['sms_send_id' => $send_id])->all();
        $smsContent = SmsContent::findOne($send->sms_content_id);

        return $this->render('sendResult', ['sendContacts' => $sendContacts, 'smsContent' => $smsContent]);
    }

    public function actionContacts()
    {
        $contacts = SmsContacts::find()->where(['control' => 1])->all();

        return $this->render('contacts', ['contacts' => $contacts]);
    }
    
    public function actionContact()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $this->layout = false;
        }
        
        $contact = $request->post('id') ? SmsContacts::findOne($request->post('id')) : new SmsContacts();

        if ($contact) {
            if (!$contact->female && !$contact->male) {
                $contact->gender = 0;
            } elseif ($contact->male) {
                $contact->gender = 1;
            } else {
                $contact->gender = 2;
            }
        }

        if (!$request->isAjax && $request->isPost) {
            if ($contact->load($request->post()) && $contact->save()) {

            }
        }

        $content = $this->render('contactForm', ['contact' => $contact]);

        if ($request->isAjax) {
            return [
                'success' => true,
                'content' => $content
            ];
        } else {
            return $content;
        }
    }
    
    public function actionContactMng($id=null)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $this->layout = false;
        }

        $contact = $id ? SmsContacts::findOne($id) : new SmsContacts();

        if ($contact->load($request->post())) {
            if ($contact->id) {
                $contact->update();
                return [
                    'success' => true,
                    'content' => 'Изменения приняты. '.Html::a('Обновить страницу', ['contacts']).'.'
                ];
            } else {
                $contact->save();
                return [
                    'success' => true,
                    'content' => 'Изменения приняты. '.Html::a('Добавить ещё контакт', null, ['onclick' => 'formContact()']).'.'
                ];
            }
        } else {
            return [
                'success' => false
            ];
        }
    }
    
    public function actionContactsUpload()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $this->layout = false;
        }
        
        $upload = new UploadFileForm();

        if (isset($_FILES) && count($_FILES) > 0) {
            /*if ($request->isPost) {
                $upload->uploadFile = UploadedFile::getInstance($upload, 'uploadFile');
                if ($upload->upload()) {
                }
            }*/

            if (!is_dir(Yii::getAlias('@app/web/uploads/sms'))) {
                mkdir(Yii::getAlias('@app/web/uploads/sms'), 0777, true);
            }

            $uploadFile = isset($_FILES[0]) ? $_FILES[0] : $_FILES;
            if (move_uploaded_file($uploadFile['tmp_name'], 'uploads/sms/' . basename($uploadFile['name']))) {
                $runConsole = new RunConsole(['file' => preg_replace('@(backend/|backend|backend\\))@', '', Yii::getAlias('@app')) . '/../yii']);

                $runConsole->run("sms/default/upload " . Yii::getAlias('@app/web/uploads/sms/') . basename($uploadFile['name']));

                return [
                    'success' => true,
                    'content' => $_FILES[0]['name']
                ];
            }

        }
        
        $content = $this->render('contactsUploadForm', ['upload' => $upload]);

        return [
            'success' => true,
            'content' => $content
        ];
    }

    public function actionContactsLoad()
    {
        header('Content-Type: application/vnd.ms-excel');
        $filename = "SmsContacts_".date("d-m-Y-His").".xlsx";
        header('Content-Disposition: attachment;filename='.$filename);
        header('Cache-Control: max-age=0');

        $objPHPExcel = (new SmsModel())->renderContactsXlsx();

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
    }

    public function actionSmsruResult()
    {
        $this->layout = false;

        $result = Yii::$app->request;
        if ($result->post('data')) {
            foreach ($result->post('data') as $entry) {
                $lines = explode("\n", $entry);
                if ($lines[0] == "sms_status") {
                    $sms_id = $lines[1];
                    $sms_status = $lines[2];

                    $smsSendContact = SmsSendContacts::findOne(['smsru_id' => $sms_id]);
                    if ($smsSendContact) {
                        $smsSendContact->smsru_result_code = $sms_status;
                        $smsSendContact->update();
                    }
                }
            }
        }

        return "100";
    }

}