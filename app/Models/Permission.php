<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * модель для таблицы 'permissions'
     */

    // связь с таблицей роли
    public function Role()
    {
        $this->belongsToMany('Role');
    }
}
