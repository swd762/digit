<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    // модель для таблицы 'modules'

    public function ModuleData() {
        $this->hasMany('App\Models\ModuleData');
    }
}
