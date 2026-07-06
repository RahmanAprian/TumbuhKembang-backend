<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = ['name', 'email', 'password', 'role', 'phone', 'address'];
    protected $hidden   = ['password', 'remember_token'];

    public function children()
    {
        return $this->hasMany(Child::class);
    }
}