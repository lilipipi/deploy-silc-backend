<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    /*
     * User State:
     * 0 = not verified
     * 1 = waiting for approval
     * 2 = verified
     * 3 = rejected
    */
    protected $fillable = [
        'name',
        'email',
        'password',
        'userState',
        'investor_type',
        'contact_no',
        'address',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    public function role() {
        return $this->hasMany(Role::class);
    }
}