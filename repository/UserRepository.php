<?php

namespace app\repository;

use app\entity\Users;
use yii\db\ActiveRecord;

class UserRepository
{
    /**
     * Создание пользователя
     * @param string $last_name Фамилия
     * @param string $first_name имя
     * @param string|null $middle_name отчетсво (не обязательно)
     * @return Users
     */
    public static function createUser(
        $last_name,
        $first_name,
        $middle_name,
    ) {
        $new_user = new Users();
        $new_user->last_name = $last_name;
        $new_user->first_name = $first_name;
        $new_user->middle_name = $middle_name;
        $new_user->save();

        return $new_user;
    }

    /**
     * Получение пользователя по where
     * @param array $where запрос
     * @return array|ActiveRecord|null
     */
    public static function findOneUser($where) {
        return Users::find()->where($where)->one();
    }
}