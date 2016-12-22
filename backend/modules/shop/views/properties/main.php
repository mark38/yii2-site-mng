<?php
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\bootstrap\ButtonDropdown;

/** @var $this \yii\web\View */
/** @var $properties \common\models\shop\ShopProperties */
/** @var $property \common\models\shop\ShopProperties */
/** @var $values \common\models\shop\ShopPropertyValues */
/** @var $value \common\models\shop\ShopPropertyValues */
/** @var $content \common\models\main\Contents */
/** @var $action */

$this->title = 'Управление свойствами и их значениями';

?>

<div class="row">
    <div class="col-md-6">
        <?php if ($property) echo $this->render('propertyMng', ['property' => $property])?>

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">
                    Перечень свойств
                    <?=ButtonDropdown::widget([
                        'label' => '<i class="glyphicon glyphicon-option-vertical"></i>',
                        'dropdown' => [
                            'items' => [
                                ['label' => 'Добавить свойство', 'url' => Url::to(['action' => 'property_add'])],
                            ],
                        ],
                        'encodeLabel' => false,
                        'options' => [
                            'class' => 'btn btn-link btn-xs root-action clear-caret',
                        ]
                    ])?>
                </h3>
            </div>
            <div class="box-body">
                <?php if ($properties) {
//                    echo $this->render('propertiesList', ['properties' => $properties]);
                }?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <?php if ($value) echo $this->render('valueMng', ['value' => $value, 'content' => $content])?>

        <?php if ($values) echo $this->render('valuesList', ['values' => $values]) ?>
    </div>
</div>
