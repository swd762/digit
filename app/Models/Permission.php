<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель для работы с таблицей допусков
 */
class Permission extends Model
{
    /**
     * Связь с таблицей ролей
     */
    public function Role()
    {
        $this->belongsToMany('Role');
    }
}
