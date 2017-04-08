<?php
use yii\bootstrap\Html;

/**
 * @var $fields \common\models\forms\FormFields
 */

$chLink = ['view', 'form_types_id' => $form->form_types_id, 'id' => $form->id];
?>

<tr>
    <?php
    foreach ($fields as $field) {
        $column = $field->name;
        switch ($field->name) {
            case 'created_at':
                echo $form->$column ? '<td>'.date('d.m.Y H:i', $form->$column).'</td>' : '<td></td>';
                break;
            case 'form_select1_id':
                echo $form->$column ? '<td>'.$form->formSelect1->name.'</td>' : '<td></td>';
                break;
//            case 'comment':
//                echo '<td title="'.$form->$column.'">'.mb_strimwidth($form->$column, 0, 100, "...").'</td>';
//                break;
            default:
                echo '<td>'.$form->$column.'</td>';
                break;
        }


    }
    ?>
<!--    <td class="text-right">-->
<!--        <ul class="list-inline">-->
<!--            <li>--><?//=Html::a('<i class="fa fa-search"></i>', $chLink, ['title' => 'Просмотр'])?><!--</li>-->
<!--        </ul>-->
<!--    </td>-->
</tr>



