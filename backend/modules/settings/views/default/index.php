<?php

use yii\grid\GridView;

use app\modules\settings\AppAsset;
AppAsset::register($this);

$this->title = 'Управление пользователями';
?>
<div class="content">
    <div class="row">
        <div class="col-sm-7">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Все пользователи</h3>
                    <div class="box-tools pull-right">
                        <a href="index?new=1"><i class="fa fa-plus-square"></i> Добавить нового пользователя</a>
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
                            'rowOptions'=>function($model){
                                if($model->status != 10){
                                    return ['class' => 'danger'];
                                }
                            },
                            'columns' => [
                                'username',
                                'email',
                                [
                                    'attribute' => 'created_at',
                                    'value' => function($data) {
                                        return date('d.m.Y', $data->created_at);
                                    }
                                ],
                                [
                                    'format' => 'raw',
                                    'attribute' => 'status',
                                    'value' => function($data) {
                                        return $data->status == 10 ? '<span class="text-success">активен</span>' : '<span class="text-danger">неактивен</span>';
                                    }
                                ],
                                [
                                    'class' => \yii\grid\ActionColumn::className(),
                                    'buttons'=>[
                                        'edit'=>function ($url, $model) {
                                            $customurl=Yii::$app->getUrlManager()->createUrl(['settings/index','id'=>$model->id]);
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
            if ($user) {
                echo $this->render('/default/mngUser', compact('user', 'roles'));
            }
            ?>
        </div>
    </div>
</div>
