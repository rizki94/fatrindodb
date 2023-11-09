<?php

namespace App\Models;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user ()
    {
        return $this->belongsTo(User::class);
    }

    public function role ()
    {
        return $this->belongsTo(Role::class);
    }
}
