<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reception extends Model
{
    /**
     * Модель для таблицы, с данными о приеме врача
     *
     * @var string
     */
    protected $table = 'receptions';

    protected $fillable = ['receipt_description', 'receipt_date'];
}
