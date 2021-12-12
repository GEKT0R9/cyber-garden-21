<?php

namespace app\entity;

use yii\db\ActiveRecord;

/**
 * Таблица пользователей
 * @property int id идентификатор
 * @property string last_name фамилия
 * @property string first_name имя
 * @property string|null middle_name отчетсво
 * @property string username логин пользователя
 * @property string email электронная почта
 * @property string password пароль
 */
class Account extends ActiveRecord
{
}