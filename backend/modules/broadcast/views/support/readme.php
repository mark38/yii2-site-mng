<?php
/** @var $this \yii\web\View */

$this->title = 'Важно';
?>

<div class="box box-default">
    <div class="box-header with-border"><h3 class="box-title">Перед началом работы</h3></div>
    <div class="box-body">
        <p>Необходимо определить следующую переменную в файле настроек <code>/common/config/params-local.php</code></p>
        <ul>
            <li>
                <code>
                    'broadcast' => [<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;'upload' => '/uploads/broadcast',<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;'clearMngUrl' => '/mng'<br>
                    ]</code>
            </li>
            <li>Адрес электронной почты для support должен совпадать с настройками mailer для отправителя.</li>
        </ul>
        <p>Каталог <code><?=Yii::getAlias('@backend/web/uploads/broadcast')?></code> должен быть создан.</p>
    </div>
</div>


