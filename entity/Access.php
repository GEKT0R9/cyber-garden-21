<?php


namespace app\entity;


use yii\db\ActiveRecord;

/**
 * Таблица access справочник доступов
 * @property int id идентификатор
 * @property int access доступ
 * @property int description описание доступа
 * @package app\entity
 */
class Access extends ActiveRecord
{

}