<?php

namespace app\modules\main\controllers;

use yii\console\Controller;
use common\models\main\Links;
use common\models\main\Ancestors;

/**
 * Default controller for the `main` module
 */
class AncestorsController extends Controller
{
    public function actionRefresh()
    {
        $links = Links::find()->all();

        if (!$links) {
            return "Links is empty\n";
        }

        $model = new Ancestors();

        /** @var Links $link */
        foreach ($links as $link) {
            if (!$link->parent) continue;

            $model->updateAncestor($link);
        }
    }
}
