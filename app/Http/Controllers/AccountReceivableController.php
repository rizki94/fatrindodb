<?php

namespace App\Http\Controllers;

use App\Models\AccountReceivable;
use App\Models\AccountReceivableDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountReceivableController extends Controller
{
    public function list(Request $request)
    {
        $status = $request->completed === "true" ? 4 : 0;
        $list = AccountReceivable::whereRaw("IF('$status' = 4, status_id = 4, status_id < 4)")->with('customer:id,name')->orderBy('status_id')->get();
        return response()->json($list);
    }

    public function update(Request $request)
    {
        $validateRequest = Validator::make([
            'status_id' => $request->data['status_id']
        ], [
            'status_id' => 'required'
        ]);
        if ($validateRequest->fails()) {
            return response()->json([
                'validateErr' => array($validateRequest->errors()->first()),
                'status' => 422,
            ]);
        } else {
            $update = AccountReceivable::where('id', $request->data['id']);
            $update->update([
                'status_id' => $request->data['status_id']
            ]);
            AccountReceivableDate::create([
                'account_receivable_id' => $request->data['id'],
                'status_id' => $request->data['status_id'],
                'user_id' => $request->data['user_id']
            ]);
        }
        return response()->json([
            'status' => 200
        ]);
    }
}
