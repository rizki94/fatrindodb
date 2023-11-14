<?php

namespace App\Http\Controllers;

use App\Models\AccountReceivableDate;
use Illuminate\Http\Request;

class AccountReceivableDateController extends Controller
{
    public function list(Request $request)
    {
        $list = AccountReceivableDate::with('status:id,name', 'user:id,name')->where('account_receivable_id',  $request->id)->orderBy('created_at')->get();
        return response()->json($list);
    }
}
