<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Role extends Model
{
    // модель для работы с таблицей 'roles'
    protected $fillable = [
        //'user_id'
    ];

    // обратное отношение один к одному
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

//    public function permissions()
//    {
//        return $this->belongsToMany('Permission');
//    }
}
