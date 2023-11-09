<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    public function Index()
    {
        $index = Company::get(['id', 'name', 'address', 'active', 'phone']);
        return response()->json($index);
    }

    public function get()
    {
        $list = Company::first(['name', 'address', 'phone']);
        return response()->json($list);
    }

    private function validateData()
    {
        $data = [
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'tax_id' => 'required',
            'tax_name' => 'required',
            'tax_address' => 'required',
            'active' => 'required',
        ];
        return $data;
    }

    public function companyCreate(Request $request)
    {
        $data = json_decode($request->data, true);
        $validateRequest = Validator::make($data, array_merge([
            'id' => 'required|min:3|max:3|unique:companies,id'
        ] ,$this->validateData()));
        if($validateRequest->fails())
        {
            return response()->json([
                'validateErr' => array($validateRequest->errors()->first()),
                'status' => 422,
            ]);
        } else {if ($request->file('image')) {
            $image = $request->file('image');
            $create = Company::create($data);
            $filename = $create->id . '.' . $image->getClientOriginalExtension();
            $image->move('uploads/companyLogo', $filename);
            $user = Company::where('id', $create->id);
            $user->update([
                'logo' => $filename
            ]);
        } else {
            Company::create($data);
        }
            return response()->json([
                'status' => 200,
                'message' => "company created"
            ]);
        }
    }

    public function companyShow(Request $request)
    {
        $company = Company::where('id', $request->id)->first();
        return response()->json($company);
    }

    public function companyUpdate(Request $request)
    {
        $logo = "";
        $data = json_decode($request->data, true);
        $id = Company::where('id', $data['id'])->pluck('id')->first();
        $validateRequest = Validator::make($data, array_merge([
            'id' => ['required', 'min:3', 'max:3', Rule::unique('companies')->ignore($id)]
        ], $this->validateData()));
        if($validateRequest->fails())
        {
            return response()->json([
                'validateErr' => array($validateRequest->errors()->first()),
                'status' => 422,
            ]);
        } else {
            $company = Company::where('id', $id);
            if ($request->file('image')) {
                $image = $request->file('image');
                $filename = $id . '.' . $image->getClientOriginalExtension();
                $image->move('uploads/companyLogo', $filename);
                $company->update(array_merge($data, [
                    'logo' => $filename
                ]));
                $logo = Company::where('id', $data['id'])->pluck('logo')->first();
            } else {
                if ($data['logo']) {
                    $path = public_path('uploads/companyLogo/' . $id . '.*');
                    $company->update($data);
                    $logo = Company::where('id', $data['id'])->pluck('logo')->first();
                } else {
                    $path = public_path('uploads/companyLogo/' . $id . '.*');
                    if ($path) {
                        File::delete(File::glob($path));
                    }
                    $company->update(array_merge($data, [
                        'logo' => ''
                    ]));
                }
            }
            return response()->json([
                'status' => 200,
                'message' => "company updated"
            ]);
        }
    }
}
