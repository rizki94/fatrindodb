<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Models\RoleUser;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where(['name' => $request->name, 'branch_id' => $request->branch_id])->first();
        $user && $role = RoleUser::where('user_id', $user->id)->pluck('role_id');
        $user && $permission = Permission::with('module')
            ->whereIn('role_id', $role)
            ->where('hasPermission', true)
            ->get()
            ->pluck('module.name');
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 401,
                'message' => 'Invalid Cradentials',
            ]);
        } else {
            $token = $user->createToken($user->name . '_Token')->plainTextToken;
            return response()->json([
                'status' => 200,
                'user' => $user,
                'token' => $token,
                'permission' => $permission,
                'message' => 'Login Successfully'
            ]);
        }
    }
    public function loginNative(Request $request)
    {
        $user = User::where(['name' => $request->name])->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 401,
                'message' => 'Invalid Cradentials',
            ]);
        } else {
            $token = $user->createToken($user->name . '_Token')->plainTextToken;
            return response()->json([
                'status' => 200,
                'token' => $token,
                'message' => 'Login Successfully'
            ]);
        }
    }

    public function systemAdminLogin(Request $request)
    {
        if ($request->password === 'ngalagena') {
            return response()->json([
                'status' => 200
            ]);
        }
    }

    public function logout(Request $request)
    {
        $user = User::where('id', $request->user['id'])->first();
        $user->tokens()->where('id', $request->token)->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Logged Out Successfully'
        ]);
    }

    public function refresh(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $role = RoleUser::where('user_id', $user->id)->pluck('role_id');
        $permission = Permission::with('module')
            ->whereIn('role_id', $role)
            ->where('hasPermission', true)
            ->get()
            ->pluck('module.name');
        $token = $user->createToken($user->name . '_Token')->plainTextToken;
        return response()->json([
            'status' => 200,
            'user' => $user,
            'token' => $token,
            'permission' => $permission,
        ]);
    }
}
