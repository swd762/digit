<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель для работы с таблицей протезно-ортопедических изделий и ее связями
 */
class Product extends Model
{

    /**
     * Имя таблицы
     */
    protected $table = 'products';

    public $timestamps = false;

    protected $fillable = ['name'];
}
