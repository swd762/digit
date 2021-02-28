<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Role extends Model
{
    /**
     *
     * модель для работы с таблицей 'roles'
     *
     *
     * @var array
     */

    protected $fillable = [
    ];

    // обратное отношение один ко мноогим
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
