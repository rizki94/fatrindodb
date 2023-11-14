<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountReceivableDate extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function status ()
    {
        return $this->belongsTo(Status::class);
    }

    public function user ()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
