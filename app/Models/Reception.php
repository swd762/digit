<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель для работы с таблицей приемов пациентов
 */
class Reception extends Model
{
    /**
     * Имя таблицы
     */
    protected $table = 'receptions';

    protected $fillable = ['receipt_description', 'receipt_date'];
}
