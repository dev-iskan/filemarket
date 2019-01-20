<?php

namespace App;

use App\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function files() {
        return $this->hasMany(File::class);
    }

    public function isAdmin () {
        return $this->hasRole('admin');
    }

    public function isTheSameUser (User $user) {
        return $this->id === $user->id;
    }

    public function sales () {
        return $this->hasMany(Sale::class);
    }
}
