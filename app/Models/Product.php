<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // модель для таблицы 'products'
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
