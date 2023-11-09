<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function active()
    {
        $profile = Profile::where('user_profile', 0)->pluck('parameter', 'id');
        return response()->json($profile);
    }

    public function list()
    {
        $profile = Profile::with('moduleGroup:id,name')->where('user_profile', 0)->orderBy('category_id', 'ASC')->orderBy('id', 'ASC')->get(['parameter', 'id', 'descr', 'category_id']);
        return response()->json($profile);
    }

    public function show(Request $request)
    {
        $list = Profile::where('id', $request->id)->first();
        return response()->json($list);

    }

    private function validateData()
    {
        $data = [            
            'id' => 'required|max:20',
            'parameter' => 'required',
            'descr' => 'required',
            'category_id' => 'required',
            'user_profile' => 'required',
        ];
        return $data;
    }

    public function store(Request $request)
    {
        $data = $request->data;
        $validateRequest = Validator::make($data, array_merge([
            'id' => 'required|unique:profiles,id'
        ], $this->validateData()));
        if($validateRequest->fails())
        {
            return response()->json([
                'validateErr' => array($validateRequest->errors()->first()),
                'status' => 422,
            ]);
        } else {
            Profile::create($data);
            return response()->json([
                'status' => 200,
                'message' => "Profile created"
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->data;
        $validateRequest = Validator::make($data, array_merge([
            'id' => ['required', Rule::unique('profiles')->ignore($id)]
        ], $this->validateData()));
        if($validateRequest->fails())
        {
            return response()->json([
                'validateErr' => array($validateRequest->errors()->first()),
                'status' => 422,
            ]);
        } else {
            $Profile = Profile::findOrFail($id);
            $Profile->update($data);
            return response()->json([
                'status' => 200,
                'message' => "Profile updated"
            ]);
        }
    }
}
