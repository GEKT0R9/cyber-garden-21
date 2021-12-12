<?php

namespace app\repository;

use app\entity\Account;
use yii\db\ActiveRecord;

class AccountRepository
{
    /**
     * Создание аккаунта
     * @param string $last_name Фамилия
     * @param string $first_name имя
     * @param string|null $middle_name отчетсво (не обязательно)
     * @param string $username логин
     * @param string $email электронная почта
     * @param string $password пароль
     * @return Account
     */
    public static function createNewAcc(
        $last_name,
        $first_name,
        $middle_name,
        $username,
        $email,
        $password
    ) {
        $new_acc = new Account();
        $new_acc->last_name = $last_name;
        $new_acc->first_name = $first_name;
        $new_acc->middle_name = $middle_name;
        $new_acc->username = $username;
        $new_acc->email = $email;
        $new_acc->password = password_hash($password, PASSWORD_DEFAULT);
        $new_acc->save();

        return $new_acc;
    }

    /**
     * Получение пользователя по where
     * @param array $where запрос
     * @return array|ActiveRecord|null
     */
    public static function findOneAccount($where) {
        return Account::find()->where($where)->one();
    }
}