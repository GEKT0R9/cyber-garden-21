<?php


namespace app\entity;


use yii\db\ActiveRecord;

/**
 * Таблица файлов
 * @property int id идентификатор
 * @property string name название файла
 * @property string file_content контент файла
 * @property int size размер файла
 * @property string permission разрешение файла
 * @package app\entity
 */
class Files extends ActiveRecord
{

}