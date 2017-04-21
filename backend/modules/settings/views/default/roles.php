<?php

use yii\grid\GridView;

$this->title = 'Управление группами пользователей';
?>
<div class="content">
    <div class="row">
        <div class="col-sm-7">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Все группы</h3>
                    <div class="box-tools pull-right">
                        <a href="roles?new=1"><i class="fa fa-plus-square"></i> Добавить новую группу</a>
                    </div>
                </div>
                <div class="box-body">
                    <ul class="list-unstyled">
                        <?=
                        GridView::widget([
                            'dataProvider' => $dataProvider,
                            'tableOptions' => [
                                'class' => 'table table-bordered'
                            ],
                            'columns' => [
                                'name',
                                'description',
                                [
                                    'class' => \yii\grid\ActionColumn::className(),
                                    'buttons'=>[
                                        'edit'=>function ($url, $model) {
                                            $customurl=Yii::$app->getUrlManager()->createUrl(['settings/roles','name'=>$model->name]);
                                            return \yii\helpers\Html::a( '<i class="fa fa-pencil-square-o"></i>', $customurl,
                                                ['title' => 'Редактировать']);
                                        }
                                    ],
                                    'template'=>'{edit}',
                                ]
                            ],
                        ]);
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-5">
            <?php
            if ($role) {
                echo $this->render('mngRole', compact('role'));
            }
            ?>
        </div>
    </div>
</div>
