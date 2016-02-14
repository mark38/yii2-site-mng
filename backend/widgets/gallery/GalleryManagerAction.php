<?php
namespace backend\widgets\gallery;

use Yii;
use yii\base\Action;
use yii\web\Response;

class GalleryManagerAction extends Action
{
    protected $behaviorName;

    /** @var  ActiveRecord */
    protected $owner;

    public function run($action=null)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
        }

        if (Yii::$app->request->isPost) {
            $action = $request->post('action');
        }

        switch ($action) {
            case 'add_group': return $this->actionAddGroup(); break;
            case 'upload_image': return $this->actionUploadImage(); break;
            case 'get_gallery': return $this->actionGetGallery(); break;
            case 'get_gallery_test': return $this->actionGetGalleryTest(); break;
            case 'delete_image': return $this->actionDeleteImage(); break;
        }
    }

    public function actionAddGroup()
    {
        $gallery_groups_id = (new Gallery())->addGroup(Yii::$app->request->post('gallery_types_id'));

        return [
            'success' => true,
            'gallery_groups_id' => $gallery_groups_id,
        ];
    }

    public function actionUploadImage()
    {
        $result = (new Gallery())->uploadImage(
            Yii::$app->request->post('route'),
            Yii::$app->request->post('gallery_types_id'),
            Yii::$app->request->post('gallery_groups_id')
        );

        if (Yii::$app->request->post('group') == 0 && Yii::$app->request->post('gallery_images_id') && $result['success']) {
            (new Gallery())->deleteImage(
                Yii::$app->request->post('route'),
                Yii::$app->request->post('gallery_images_id')
            );
        }

        return $result;
    }

    public function actionGetGallery()
    {
        //$this->layout = false;

        return [
            'success' => true,
            'gallery' => $this->render('galleryManager', [
                'group' => Yii::$app->request->post('group') == 1 ? true : false,
                'gallery_groups_id' => Yii::$app->request->post('gallery_groups_id'),
                'gallery_images_id' => Yii::$app->request->post('gallery_images_id'),
            ]),
        ];
    }

    public function actionDeleteImage()
    {
        (new Gallery())->deleteImage(
            Yii::$app->request->post('route'),
            Yii::$app->request->post('gallery_images_id')
        );

        return [
            'success' => 'true'
        ];
    }
}