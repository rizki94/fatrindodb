<?php

namespace App\Models;

use App\Models\User;
use App\Models\Module;
use App\Models\RoleUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
