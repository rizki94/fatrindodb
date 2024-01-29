<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function salesman()
    {
        return $this->belongsTo(Salesman::class, 'sales_id', 'id');
    }
}
