<?php

namespace App\Http\Controllers;

use App\Models\Prefix;
use App\Models\Profile;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
    public function userProfile (Request $request)
    {
        $userProfile = UserProfile::where('user_id', $request->user_id)
        ->pluck('parameter', 'profile_id');
        return response()->json($userProfile);
    }

    public function userProfileList()
    {
        $data = Profile::where('user_profile', 1)->get(['id', 'category_id', 'descr']);
        return response()->json($data);
    }

    public function prefixList()
    {
        $data = Prefix::get(['id', 'group_code_id']);
        return response()->json($data);
    }

    public function userProfileStore(Request $request)
    {
        $arr = json_decode($request->getContent(), true);
        foreach($arr['data'] as $key => $value){
            UserProfile::updateOrCreate([
                'user_id' => $arr['id'],
                'profile_id' => $key,
            ],
            [
                'parameter' => $value
            ]);
        };
        return response()->json([
            'status' => 200,
            'message' => 'Perubahan berhasil disimpan'
        ]);
    }
}
