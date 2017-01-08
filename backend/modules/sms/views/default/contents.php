<?php
use yii\bootstrap\Html;

/** @var $this \yii\web\View */
/** @var $contents \common\models\sms\SmsContent */

$this->title = 'SMS-сообщения';

?>

<div class="row">
    <div class="col-md-7 col-sm-12">

        <div class="box box-default">
            <div class="box-header with-border"><?=Html::a('Добавить', ['content'], ['class' => 'btn btn-primary btn-flat btn-sm'])?></div>
            <div class="box-body">
                <table class="table table-hover table-condensed">
                    <thead>
                    <tr><th>#</th><th>Сообщение (комментарий/текст)</th><th>Отправок</th><th class="text-right">Действие</th></tr>
                    </thead>
                    <tbody>
                    <?php if ($contents) {
                        foreach ($contents as $i => $content) {
                            echo $this->render('content', ['content' => $content, 'num' => $i + 1]);
                        }
                    } else {
                        echo '<tr><td colspan="4" class="text-center"><em class="text-muted">Нет сообщений</em></td></tr>';
                    } ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
