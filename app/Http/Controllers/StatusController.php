<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function list()
    {
        $list = Status::get(['id', 'name']);
        return response()->json($list);
    }
}
