<?php
namespace app\modules\user\models;

use Yii;
use common\models\User;
use yii\base\Model;
use yii\web\IdentityInterface;

class ProfileForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $password_repeat;
    public $user_id;

    public function rules()
    {
        return [
            [['username', 'email'], 'trim'],
            [['username', 'email'], 'required'],
            [['username', 'email'], 'string', 'max' => 255],
            [['user_id'], 'integer'],
            [['email'], 'email'],
            /*[['password', 'password_repeat'], 'required'],*/
            [['password', 'password_repeat'], 'string', 'min' => 6],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],
        ];
    }

    public function save()
    {
        $user = User::findOne($this->user_id);
        $user->username = $this->username;
        $user->email = $this->email;
        if ($this->password) {
            $user->setPassword($this->password);
            $user->generateAuthKey();
        }
        $user->save();

        return true;
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Пользователь',
            'email' => 'Электронная почта',
            'password' => 'Пароль',
            'password_repeat' => 'Повтор папроя',
        ];
    }
}