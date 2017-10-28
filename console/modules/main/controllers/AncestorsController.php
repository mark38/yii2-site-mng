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
        $links = Links::findOne(12);

        if (!$links) {
            return "Links is empty\n";
        }

        $model = new Ancestors();

        if (count($links)) {
            $model->updateAncestor($links);
            return true;
        }

        /** @var Links $link */
        foreach ($links as $link) {
            $model->updateAncestor($link);
        }

        return true;
    }
}
