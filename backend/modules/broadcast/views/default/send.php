<?php
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/** @var $this \yii\web\View */
/** @var $broadcast_address \common\models\broadcast\BroadcastAddress */

$this->title = 'Рассылка уведомлений';

$this->params['breadcrumbs'][] = ['label' => 'Рассылка', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Управление';

?>

<div class="box box-default">
    <div class="box-body">
        <ul class="list-unstyled">
            <li>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="checkbox"><?=Html::checkbox('check_user_all', true, ['id' => 'check-user-all', 'label' => 'Выделить все', 'labelOptions' => ['style' => 'font-weight: normal'], 'disabled' => (Yii::$app->request->get('action') == 'send' ? true : false)])?></div>
                    </div>
                    <div class="col-sm-8">
                        <?php if (Yii::$app->request->get('action') == 'send') {
                            echo Html::tag('div', 'Идёт отправка ...', [
                                'class' => 'text-danger',
                                'id' => 'mail-send-status',
                                'data-broadcast-send-id' => Yii::$app->request->get('broadcast_send_id'),
                                'data-status-url' => Url::to(['status'])
                            ]);
                        } else {
                            echo Html::a('<i class="fa fa-paper-plane" aria-hidden="true"></i> Отправить', '#', [
                                'class' => 'btn btn-sm btn-success btn-flat',
                                'data-url-redirect' => Url::current(['action' => 'send']),
                                'data-url-address' => Url::to(['address']),
                                'id' => 'brodcast-send',
                            ]);
                        }?>
                    </div>
                </div>
            </li>
            <li><hr></li>
            <?php
            /** @var $address \common\models\broadcast\BroadcastAddress */
            if (ArrayHelper::getColumn($broadcast_address, 'user_id')) {
                echo Html::tag('li', '<h3>Зарегистрированные пользователи</h3>');

                $user_exist = false;
                foreach ($broadcast_address as $address) {
                    if ($address->user_id) {
                        $user_exist = true;
                        echo Html::tag('li',
                            '<div class="checkbox">' .
                            Html::checkbox('check_user_'.$address->id, true, [
                                'label' => Html::tag('strong', $address->user->email).' &mdash; '.$address->user->username,
                                'data-checkbox-address-id' => $address->id,
                                'class' => 'check-user',
                                'disabled' => (Yii::$app->request->get('action') == 'send' ? true : false)
                            ]) .
                            (Yii::$app->request->get('action') == 'send' ? Html::tag('span', 'Ожидание', ['id' => 'status-'.$address->id, 'class' => 'text-danger', 'style' => 'margin-left: 15px;']) : '') .
                            '</div>'
                        );
                    }
                }

                if (!$user_exist) {
                    echo Html::tag('li', '<em class="text-muted">Не заданы</em>');
                }
            }

            if (ArrayHelper::getColumn($broadcast_address, 'email')) {
                echo Html::tag('li', '<h3>Незарегистрированные пользователи</h3>');

                $user_exist = false;
                foreach ($broadcast_address as $address) {
                    if ($address->email) {
                        $user_exist = true;
                        echo Html::tag('li',
                            '<div class="checkbox">' .
                            Html::checkbox('check_user_'.$address->id, true, [
                                'label' => Html::tag('strong', $address->email).($address->fio ? ' &mdash; '.$address->fio : ''),
                                'data-checkbox-address-id' => $address->id,
                                'class' => 'check-user',
                                'disabled' => (Yii::$app->request->get('action') == 'send' ? true : false)
                            ]) .
                            (Yii::$app->request->get('action') == 'send' ? Html::tag('span', 'Ожидание', ['id' => 'status-'.$address->id, 'class' => 'text-danger', 'style' => 'margin-left: 15px;']) : '') .
                            '</div>'
                        );
                    }
                }

                if (!$user_exist) {
                    echo Html::tag('li', '<em class="text-muted">Не заданы</em>');
                }
            }

            ?>
        </ul>
    </div>
</div>



