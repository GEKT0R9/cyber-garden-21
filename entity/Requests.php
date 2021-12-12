<?php


namespace app\entity;


use yii\db\ActiveRecord;

/**
 * Таблица заявок
 * @property int id идентификатор
 * @property string title Заголовок
 * @property string description Описание
 * @property int before_img_id идентификатор фото до
 * @property int after_img_id идентификатор фото после
 * @property string date дата создания
 * @property int status_id идентификатор статуса
 * @property int create_user_id идентификатор пользователя
 *
 * @property DirCriterion[] criteria критерии
 * @property Files before_img фото до
 * @property DirStatusType status статус
 * @property Users create_user пользователь создавший заявку
 * @package app\entity
 */
class Requests extends ActiveRecord
{
    public function getCriteria()
    {
        return $this->hasMany(DirCriterion::className(), ['id' => 'criterion_id'])
            ->viaTable('request_to_criterion',['request_id' => 'id']);
    }

    public function getBefore_img()
    {
        return $this->hasOne(Files::className(), ['id' => 'before_img_id']);
    }

    public function getAfter_img()
    {
        return $this->hasOne(Files::className(), ['id' => 'after_img_id']);
    }

    public function getStatus()
    {
        return $this->hasOne(StatusOrder::className(), ['id' => 'status_id']);
    }

    public function getCreate_user()
    {
        return $this->hasOne(Users::className(), ['id' => 'create_user_id']);
    }
}