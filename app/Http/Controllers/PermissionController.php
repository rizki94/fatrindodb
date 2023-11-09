<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\RoleUser;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function permissionList(Request $request)
    {
        $list = Permission::where('role_id', $request->role_id)->get(['module_id', 'haspermission']);
        return response()->json($list);
    }
}
