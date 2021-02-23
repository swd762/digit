<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    // модель для таблицы 'modules'

    public function moduleData() {
        $this->hasMany(ModuleData::class);
    }
}
