<?php


namespace app\entity;


use yii\db\ActiveRecord;

/**
 * Таблица roles справочник ролей
 * @property int id идентификатор
 * @property string name название роли
 * @property string description описание роли
 *
 * @property Access[] accesses доступы
 * @package app\entity
 */
class Roles extends ActiveRecord
{
    public function getAccesses()
    {
        return $this->hasMany(Access::className(), ['id' => 'access_id'])
            ->viaTable('role_to_access',['role_id' => 'id']);
    }
}