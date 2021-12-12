<?php


namespace app\entity;


use yii\db\ActiveRecord;

/**
 * Таблица request_to_criterion связующая таблица
 * @property int request_id идентификатор заявки
 * @property int criterion_id идентификатор критерия
 * @package app\entity
 */
class RequestToCriterion extends ActiveRecord
{

}