<?php

namespace App\Http\Controllers;

use App\Models\AccountReceivable;
use App\Models\AccountReceivableTemp;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountReceivableTempController extends Controller
{

    private function validateData()
    {
        $data = [
            '*.invoice' => 'required',
            '*.date' => 'required|date',
            '*.due_date' => 'required|date',
            '*.outlet_code' => 'required',
            '*.outlet_name' => 'required',
            '*.amount' => 'required|numeric',
        ];
        return $data;
    }

    private function validateDataAccountReceivables()
    {
        $data = [
            '*.invoice' => 'required',
            '*.date' => 'required|date',
            '*.due_date' => 'required|date',
            '*.outlet_code' => 'required',
            '*.amount' => 'required|numeric',
        ];
        return $data;
    }

    public function import(Request $request)
    {
        $arr = json_decode($request->getContent(), true);
        $datas = $arr['data'];
        $validateRequest = Validator::make($datas, $this->validateData());
        if ($validateRequest->fails()) {
            return response()->json([
                'validateErr' => array($validateRequest->errors()->first()),
                'status' => 422,
            ]);
        } else {
            AccountReceivableTemp::truncate();
            foreach ($datas as $data) {
                AccountReceivableTemp::create([
                    'invoice' => $data['invoice'],
                    'date' => $data['date'],
                    'due_date' => $data['due_date'],
                    'outlet_code' => $data['outlet_code'],
                    'outlet_name' => $data['outlet_name'],
                    'amount' => $data['amount'],
                ]);
            }
            return response()->json([
                'status' => 200
            ]);
        }
    }

    public function convert(Request $request)
    {
        $arr = json_decode($request->getContent(), true);
        $datas = $arr['data'];
        $validateRequest = Validator::make($datas, $this->validateDataAccountReceivables());
        if ($validateRequest->fails()) {
            return response()->json([
                'validateErr' => array($validateRequest->errors()->first()),
                'status' => 422,
            ]);
        } else {
            foreach ($datas as $data) {
                AccountReceivable::updateOrCreate([
                    'invoice' => $data['invoice']
                ], [
                    'date' => $data['date'],
                    'due_date' => $data['due_date'],
                    'outlet_code' => $data['outlet_code'],
                    'amount' => $data['amount'],
                ]);
                Customer::updateOrCreate([
                    'id' => $data['outlet_code'],
                    'name' => $data['outlet_name'],
                ]);
            }
            AccountReceivable::whereNotIn('invoice', collect($datas)->map(function($row) {
                return $row['invoice'];
            }))->update([
                'status_id' => 4
            ]);
            return response()->json([
                'status' => 200
            ]);
        }
    }

    public function statusImport()
    {
        $list = AccountReceivableTemp::leftJoin('account_receivables', 'account_receivables.invoice', '=', 'account_receivable_temps.invoice')
            ->groupBy('account_receivable_temps.invoice')
            ->selectRaw("account_receivable_temps.*, IF(account_receivables.invoice is not null, 'Faktur Sudah Pernah Diupload', 'Tidak Ada Masalah') as message, IF(account_receivables.invoice is not null, 0, 1) as status")
            ->orderBy('status')
            ->get();
        return response()->json($list);
    }
}
