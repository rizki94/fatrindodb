<?php

namespace App\Http\Controllers;

use App\Models\ModuleGroup;
use Illuminate\Http\Request;

class ModuleGroupController extends Controller
{
    public function moduleGroups()
    {
        $list = ModuleGroup::get(['id', 'name', 'description', 'active']);
        return response()->json($list);
    }

    public function activeModuleGroups()
    {
        $list = ModuleGroup::where('active', 1)->get(['id', 'name', 'description', 'active']);
        return response()->json($list);
    }
}
