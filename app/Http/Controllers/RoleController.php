<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function activeRole()
    {
        $list = Role::where('active', 1)->get();
        return response()->json($list);
    }

    public function roleList()
    {
        $list = Role::get(['id', 'name', 'active']);
        return response()->json($list);
    }

    public function roleShow(Request $request)
    {
        $role = Role::where('id', $request->id)->first();
        return response()->json($role);
    }

    private function validateData()
    {
        $data = [
            'name' => 'required',
            'active' => 'required',
        ];
        return $data;
    }

    public function roleCreate(Request $request)
    {
        $role = $request->role;
        $permission = $request->permission;

        $validateRequest = Validator::make($role, [
            'name' => 'required',
            'active' => 'required'
        ]);
        if($validateRequest->fails())
        {
            return response()->json([
                'validateErr' => array($validateRequest->errors()->first()),
                'status' => 422,
            ]);
        } else {
            $createRole = Role::create($role);
            for($x=0; $x < count($request->permission) ; $x++) {
                Permission::updateOrCreate([
                    'role_id' => $createRole->id,
                    'module_id' => $permission[$x]['id'],
                ], [
                    'haspermission' => $permission[$x]['haspermission']
                ]);
            }
            return response()->json([
                'status' => 200,
                'message' => 'Role berhasil ditambahkan'
            ]);
        }
    }

    public function roleUpdate(Request $request, $id)
    {
        $role = $request->role;
        $permission = $request->permission;
        $validateRequest = Validator::make($role,  $this->validateData());
        if($validateRequest->fails())
        {
            return response()->json([
                'validateErr' => array($validateRequest->errors()->first()),
                'status' => 422,
            ]);
        } else {
            $updateRole = Role::findOrFail($id);
            $updateRole->update($role);
            for($x=0; $x < count($request->permission) ; $x++) {
                Permission::updateOrCreate([
                    'role_id' => $role['id'],
                    'module_id' => $permission[$x]['id'],
                ], [
                    'haspermission' => $permission[$x]['haspermission']
                ]);
            }
            return response()->json([
                'status' => 200,
                'message' => "Role updated"
            ]);
        }
    }
}
