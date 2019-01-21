<?php

namespace App;

use App\Traits\HasRoles;
use Carbon\Carbon;
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
        'name', 'email', 'password', 'stripe_id', 'stripe_key'
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

    public function saleValueOverLifetime () {
        return $this->sales->sum('sale_price');
    }

    public function saleValueOverMonth () {
        $now = Carbon::now();
        return $this->sales()->whereBetween('created_at', [
            $now->startOfMonth(),
            $now->copy()->endOfMonth() // copy same value in order to avoid same reference to object
        ])->get()->sum('sale_price');
    }
}
