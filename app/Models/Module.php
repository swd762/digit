<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    /**
     * модель для таблицы 'modules'
     */

    // связь с таблицей 'module data'
    public function moduleData() {
        $this->hasMany(ModuleData::class);
    }
}
