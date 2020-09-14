<?php

namespace App;
//namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;



class User extends Authenticatable implements JWTSubject
{
    // Модель для работы с таблицей 'users', также для аутентификаци

    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // метод для работы с JWT токеном
    public function getJWTIdentifier() {
        return $this->getKey();
    }
    // метод для работы с JWT токеном
    public function getJWTCustomClaims() {
            return [];
    }

    // свзяь один ко многим с таблицей 'roles'
    public function Role() {
        return $this->hasOne('App\Models\Role');
    }
}
