<?php

namespace app\modules\auctionmb\controllers;

use yii\web\Controller;

/**
 * Default controller for the `auctionmb` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionTypes()
    {
        return $this->render('types');
    }
}
