<?php


namespace app\entity;


use yii\db\ActiveRecord;

/**
 * Таблица role_to_access справочник ролей
 * @property int role_id идентификатор роли
 * @property int access_id идентификатор доступа
 * @package app\entity
 */
class RoleToAccess extends ActiveRecord
{

}