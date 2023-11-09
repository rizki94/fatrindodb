<?php

namespace App\Http\Controllers;

use App\Models\Module;

class ModuleController extends Controller
{
    public function activeModules ()
    {
        $modules = Module::where('active', 1)->get(['id', 'module_group_id', 'name', 'description', 'active']);
        return response()->json($modules);
    }

    public function index ()
    {
        $modules = Module::get(['id', 'module_group_id', 'name', 'description', 'active']);
        return response()->json($modules);
    }
}
