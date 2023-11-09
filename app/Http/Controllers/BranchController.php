<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{
    public function index()
    {
        $index = Branch::get(['id', 'name', 'active', 'company_id']);
        return response()->json($index);
    }

    public function activeBranch()
    {
        $list = Branch::where('active',  1)->get(['id', 'name']);
        return response()->json($list);
    }

    private function validateData()
    {
        $data = [
            'name' => 'required',
        ];
        return $data;
    }

    public function branchCreate(Request $request)
    {
        $data = $request->data;
        $validateRequest = Validator::make($data, array_merge([
            'id' => 'required|min:3|max:3|unique:branches,id'
        ] ,$this->validateData()));
        if($validateRequest->fails())
        {
            return response()->json([
                'validateErr' => array($validateRequest->errors()->first()),
                'status' => 422,
            ]);
        } else {
            Branch::create($data);
            return response()->json([
                'status' => 200,
                'message' => "branch created"
            ]);
        }
    }

    public function branchShow(Request $request)
    {
        $branch = Branch::where('id', $request->id)->first();
        return response()->json($branch);
    }

    public function branchUpdate(Request $request, Branch $branch, $id)
    {
        $data = $request->data;
        $validateRequest = Validator::make($data, array_merge([
            'id' => ['required', 'min:3', 'max:3', Rule::unique('branches')->ignore($id)]
        ], $this->validateData()));
        if($validateRequest->fails())
        {
            return response()->json([
                'validateErr' => array($validateRequest->errors()->first()),
                'status' => 422,
            ]);
        } else {
            $branch = Branch::findOrFail($id);
            $branch->update($data);
            return response()->json([
                'status' => 200,
                'message' => "branch updated"
            ]);
        }
    }
}
