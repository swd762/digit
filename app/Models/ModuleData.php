<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleData extends Model
{
    // модель для таблицы 'module_data'
    protected $fillable = [
        'module_key',
        'data'
    ];
    public function Module() {
        $this->belongsTo('App\Models\Module');
    }
}
