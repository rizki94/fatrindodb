<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
    public function list()
    {
        $list = Activity::with('user:id,name')->orderBy('id', 'DESC')->get();
        return response()->json($list);
    }
}
