<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    // модель для работы с таблицей 'roles'
    protected $fillable = [
        'user_id'
    ];

    // обратное отношение один к одному
    public function User() {
        return $this->belongsTo('App\User');
    }
}
