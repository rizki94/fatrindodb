<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function userList()
    {
        $user = User::all();
        return response()->json($user);
    }

    private function validateData()
    {
        $data = [
            'full_name' => 'required',
            'is_salesman' => 'required',
            'branch_id' => 'required|min:1',
            'active' => 'required'
        ];
        return $data;
    }

    private function storeData($data)
    {
        $data = [
            'name' => $data['name'],
            'full_name' => $data['full_name'],
            'is_salesman' => $data['is_salesman'],
            'group_sales_id' => $data['group_sales_id'],
            'branch_id' => $data['branch_id'],
            'active' => $data['active'],
        ];
        return $data;
    }

    public function userCreate(Request $request)
    {
        $data = json_decode($request->data, true);
        $validateRequest = Validator::make($data, array_merge([
            'name' => 'required|min:3|unique:users,name',
            'password' => 'required|min:8',
            'group_sales_id' => 'required_if:is_salesman,1,true'
        ], $this->validateData()));
        if ($validateRequest->fails()) {
            return response()->json([
                'validateErr' => array($validateRequest->errors()->first()),
                'status' => 422,
            ]);
        } else {
            if ($request->file('image')) {
                $image = $request->file('image');
                $create = User::create(
                    array_merge([
                        'remember_token' => Str::random(10),
                        'password' => bcrypt($data['password'])
                    ], $this->storeData($data))
                );
                $filename = $create->id . '.' . $image->getClientOriginalExtension();
                $image->move('uploads/avatar', $filename);
                $user = User::where('id', $create->id);
                $user->update([
                    'avatar_img' => $filename
                ]);
            } else {
                $create = User::create(
                    array_merge([
                        'remember_token' => Str::random(10),
                        'password' => bcrypt($data['password'])
                    ], $this->storeData($data)),
                );
            }
            return response()->json([
                'status' => 200,
                'message' => "user created"
            ]);
        }
    }

    public function userShow(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        return response()->json($user);
    }

    public function userUpdate(Request $request)
    {
        $data = json_decode($request->data, true);
        $id = User::where('id', $data['id'])->pluck('id')->first();
        $validateRequest = Validator::make($data, array_merge([
            'name' => ['required', 'min:3', Rule::unique('users')->ignore($id)],
            'password' => 'nullable|min:8',
            'group_sales_id' => 'required_if:is_salesman,1,true'
        ], $this->validateData()));
        if ($validateRequest->fails()) {
            return response()->json([
                'validateErr' => $validateRequest->errors(),
                'status' => 422,
            ]);
        } else {
            $user = User::where('id', $id);
            if ($request->file('image')) {
                $image = $request->file('image');
                $filename = $id . '.' . $image->getClientOriginalExtension();
                $image->move('uploads/avatar', $filename);
                $user->update(array_merge($this->storeData($data), [
                    'avatar_img' => $filename
                ]));
            } else {
                if ($data['avatar_img']) {
                    $path = public_path('uploads/avatar/' . $id . '.*');
                    $user->update($this->storeData($data));
                    $avatar = User::where('id', $data['id'])->pluck('avatar_img')->first();
                } else {
                    $path = public_path('uploads/avatar/' . $id . '.*');
                    if ($path) {
                        File::delete(File::glob($path));
                    }
                    $user->update(array_merge($this->storeData($data), [
                        'avatar_img' => ''
                    ]));
                }
            }
            if (strlen($data['password']) !== 0) {
                $user->update([
                    'password' => bcrypt($data['password'])
                ]);
            }
            return response()->json([
                'status' => 200,
                'message' => "user updated",
            ]);
        }
    }

    public function changePassword(Request $request)
    {
        $data = json_decode($request->data, true);
        $id = User::where('id', $data['id'])->pluck('id')->first();
        $validateRequest = Validator::make($data, [
            'password' => 'nullable|min:8',
        ]);
        if ($validateRequest->fails()) {
            return response()->json([
                'validateErr' => $validateRequest->errors(),
                'status' => 422,
            ]);
        } else {
            $user = User::where('id', $id);
            if ($request->file('image')) {
                $image = $request->file('image');
                $filename = $id . '.' . $image->getClientOriginalExtension();
                $image->move('uploads/avatar', $filename);
                $user->update([
                    'avatar_img' => $filename
                ]);
            } else {
                if (!$data['avatar_img']) {
                    $path = public_path('uploads/avatar/' . $id . '.*');
                    if ($path) {
                        File::delete(File::glob($path));
                    }
                    $user->update([
                        'avatar_img' => ''
                    ]);
                }
            }
            if (strlen($data['password']) !== 0) {
                $user->update([
                    'password' => bcrypt($data['password'])
                ]);
            }
            return response()->json([
                'status' => 200,
                'message' => "User updated",
            ]);
        }
    }

    public function indexsales()
    {
        $salesman = User::SalesUser()->get();
        return response()->json($salesman);
    }
}
