<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    // модель для таблицы 'patients'
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
