<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

/**
 * Модель для работы с таблицей ролей
 */
class Role extends Model
{
    /**
     * Связь с таблицей пользователей
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
