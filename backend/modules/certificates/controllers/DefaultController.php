<?php

namespace app\modules\certificates\controllers;

use app\modules\certificates\models\CertificatesHelper;
use common\models\certificates\CertificatesLost;
use common\models\certificates\FilePaths;
use common\models\certificates\RequestedCertificates;
use common\models\certificates\Requests;
use common\models\certificates\Tasks;
use common\models\main\Companies;
use Yii;
use common\models\certificates\Certificates;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

/**
 * Default controller for the `certificate` module
 */
class DefaultController extends Controller
{

    public function actionIndex() {
        $current_task = Tasks::find()->where(['state' => 1])->one();
        $tasks = new ActiveDataProvider([
            'query' => Tasks::find()->orderBy(['state' => SORT_DESC, 'created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'current_task' => $current_task,
            'tasks' => $tasks
        ]);
    }

    public function actionRequests($tasks_id = null, $id = null, $new = null)
    {
        if ($tasks_id) {
            $task = Tasks::findOne($tasks_id);
        } elseif (!Tasks::find()->where(['state' => 1])->one()) {
            $task = new Tasks();
            $task->save();
        } else {
            $task  = Tasks::find()->where(['state' => 1])->one();
        }

        $certificates = Certificates::find()->where(['state' => 1])->all();

        if ($new) {
            $request = new Requests();
            $requested_certificates = [];
            foreach ($certificates as $certificate) {
                $requested_certificates[$certificate->id] = new RequestedCertificates();
                $requested_certificates[$certificate->id]->certificates_id = $certificate->id;
            }
        } else {
            $request = Requests::findOne($id);
            $requested_certificates = [];
            foreach ($certificates as $certificate) {
                if ($request && RequestedCertificates::find()->where(['requests_id' => $request->id, 'certificates_id' => $certificate->id])->one()) {
                    $requested_certificates[$certificate->id] = RequestedCertificates::find()->where(['requests_id' => $request->id, 'certificates_id' => $certificate->id])->one();
                } else {
                    $requested_certificates[$certificate->id] = new RequestedCertificates();
                    $requested_certificates[$certificate->id]->certificates_id = $certificate->id;
                }
            }


        }
        $companies = ArrayHelper::map(Companies::find()->all(), 'id', 'name');
        $requests = new ActiveDataProvider([
            'query' => Requests::find()->where(['tasks_id' => $tasks_id]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        if ($request && $request->load(Yii::$app->request->post())) {

            $request->tasks_id = $task->id;
            $request->save();

            if (Model::loadMultiple($requested_certificates, Yii::$app->request->post())) {
                foreach ($requested_certificates as $key => $requested_certificate) {
                    if (!$requested_certificate->wagons) {
                        $requested_certificate->delete();
                        continue;
                    }
                    $requested_certificate->requests_id = $request->id;
                    $requested_certificate->save();
                }
            }

            if ($new) {
                Yii::$app->getSession()->setFlash('success', 'Запрос добавлен');
            } else {
                Yii::$app->getSession()->setFlash('success', 'Изменения сохранены');
            }

        } else {
            return $this->render('requests', [
                'task' => $task,
                'companies' => $companies,
                'requests' => $requests,
                'request' => $request,
                'requested_certificates' => $requested_certificates,
                'new' => $new,
                'excels' => FilePaths::find()->where(['tasks_id' => $tasks_id, 'type' => 'excel'])->all() ? FilePaths::find()->where(['tasks_id' => $tasks_id, 'type' => 'excel'])->all() : '',
                'excel_zip' => FilePaths::find()->where(['tasks_id' => $tasks_id, 'type' => 'excel-zip'])->one() ? FilePaths::find()->where(['tasks_id' => $tasks_id, 'type' => 'excel-zip'])->one()->path : '',
                'companies_zip' => FilePaths::find()->where(['tasks_id' => $tasks_id, 'type' => 'companies-zip'])->all() ? FilePaths::find()->where(['tasks_id' => $tasks_id, 'type' => 'companies-zip'])->all() : '',
                'lost' => CertificatesLost::find()->where(['tasks_id' => $tasks_id])->all() ? CertificatesLost::find()->where(['tasks_id' => $tasks_id])->all() : ''
            ]);
        }
        return $this->redirect(['/certificates/requests', 'tasks_id' => $task->id]);
    }

    public function actionDelRequest($id) {
        $request = Requests::findOne($id);
        if ($request->delete()) {
            Yii::$app->getSession()->setFlash('success', 'Запрос удален');
        } else {
            Yii::$app->getSession()->setFlash('error', 'Возникли проблемы. Попробуйте позже');
        }
        return $this->redirect(['/certificates/requests', 'tasks_id' => $request->tasks_id]);
    }

    public function actionDelTask($id) {
        $task = Tasks::findOne($id);
        if ($task->delete()) {
            Yii::$app->getSession()->setFlash('success', 'Задача удалена');
        } else {
            Yii::$app->getSession()->setFlash('error', 'Возникли проблемы. Попробуйте позже');
        }
        return $this->redirect(['/certificates/index']);
    }

    public function actionList($id = null, $new = null)
    {
        $certificate = $new ? new Certificates() : Certificates::findOne($id);
        $certificates = new ActiveDataProvider([
            'query' => Certificates::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        if ($certificate && $certificate->load(Yii::$app->request->post()) && $certificate->save()) {
            if ($new) {
                Yii::$app->getSession()->setFlash('success', 'Справка добавлена');
            } else {
                Yii::$app->getSession()->setFlash('success', 'Изменения сохранены');
            }

        } else {
            return $this->render('list', [
                'certificates' => $certificates,
                'certificate' => $certificate,
                'new' => $new
            ]);
        }
        return $this->redirect(['/certificates/list']);
    }

    public function actionDelCertificate($id) {
        $certificate = Certificates::findOne($id);
        if ($certificate->delete()) {
            Yii::$app->getSession()->setFlash('success', 'Справка удалена');
        } else {
            Yii::$app->getSession()->setFlash('error', 'Возникли проблемы. Попробуйте позже');
        }
        return $this->redirect(['/certificates/list']);
    }

    public function actionCompanies($id = null, $new = null)
    {
        $company = $new ? new Companies() : Companies::findOne($id);
        $companies = new ActiveDataProvider([
            'query' => Companies::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        if ($company && $company->load(Yii::$app->request->post()) && $company->save()) {
            if ($new) {
                Yii::$app->getSession()->setFlash('success', 'Компания добавлена');
            } else {
                Yii::$app->getSession()->setFlash('success', 'Изменения сохранены');
            }

        } else {
            return $this->render('companies', [
                'companies' => $companies,
                'company' => $company,
                'new' => $new
            ]);
        }
        return $this->redirect(['/certificates/companies']);
    }

    public function actionDelCompany($id) {
        $company = Companies::findOne($id);
        if ($company->delete()) {
            Yii::$app->getSession()->setFlash('success', 'Компания удалена');
        } else {
            Yii::$app->getSession()->setFlash('error', 'Возникли проблемы. Попробуйте позже');
        }
        return $this->redirect(['/certificates/companies']);
    }

    public function actionCloseTask($tasks_id)
    {
        if ($tasks_id != Tasks::find()->where(['state' => 1])->one()->id) {
            Yii::$app->getSession()->setFlash('error', 'Возникли проблемы. Попробуйте позже');
        } else {
            $task = Tasks::find()->where(['id' => $tasks_id])->one();
            $task->state = 0;
            $task->save();
            (new CertificatesHelper())->createExcels($task->id);
        }
        return $this->redirect(['/certificates/requests', 'tasks_id' => $tasks_id]);
    }

    public function actionTxtUpload($tasks_id) {
        if (Yii::$app->request->post()) {
            if ($_FILES['file']['type'] == 'text/plain') {
                if ((new CertificatesHelper())->moveFile($_FILES, 'temp_txt')) {
                    exec(Yii::getAlias('@app').'/../yii certificates/load-from-certificate/load-from-file '.$_FILES['file']['name']);
                    return json_encode(['success' => 'Ок!']);
                } return json_encode(['error' => 'Ошибка при записи файла на сервер']);
            } else {
                return json_encode(['error' => $_FILES['file']['name'].' неверного формата.']);
            }
        }
        return $this->render('load-wagons');
    }

    public function actionCreateCompaniesZip($tasks_id)
    {
        (new CertificatesHelper())->createCompaniesZip($tasks_id);
        return $this->redirect(['/certificates/requests', 'tasks_id' => $tasks_id]);
    }
}
