<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'user_id'
    ];
    public function User() {
        return $this->belongsTo('App\User');
    }
}
