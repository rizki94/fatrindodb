<?php

namespace App\Http\Controllers;

use App\Models\RoleUser;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class RoleUserController extends Controller
{
    public function UserRoleList(Request $request)
    {
        $data = RoleUser::where('user_id', $request->user_id)->get();
        return response()->json($data);
    }

    public function storeRoleUser(Request $request)
    {
        $user_id = $request->user_id;
        $role_id = $request->role_id;
        $array = [];
            RoleUser::where('user_id', $user_id)->whereNotIn('role_id', $role_id)->delete();
            foreach ($role_id as $item) {
                if ($item)
                RoleUser::updateOrCreate([
                    'user_id' => $user_id,
                    'role_id' => $item
                ]);
            }
            return response()->json([
                'status' => 200,
                'array' => $array,
                'message' => "role ditambahkan"
            ]);
    }

    public function deleteRoleUser(Request $request)
    {
        RoleUser::where(['user_id' => $request->user_id], ['role_id' => $request->role_id])->delete();
        return response()->json([
            'status' => 200,
            'message' => "role dihapus"
        ]);
    }
}
