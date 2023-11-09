<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['name', 'full_name', 'password', 'is_salesman', 'branch_id', 'active', 'remember_token'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function ScopeSalesUser($query)
    {
        return $query->where('is_salesman', 1);
    }

    public function transactions ()
    {
        return $this->hasMany(Transaction::class);
    }

    public function contacts ()
    {
        return $this->hasMany(Contact::class);
    }

    public function permissions ()
    {
        return $this->belongsTo(Permission::class);
    }

    public function role ()
    {
        return $this->belongsTo(Role::class);
    }

}
