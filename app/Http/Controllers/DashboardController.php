<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function salesBySalesman(Request $request)
    {
        $startDate = Carbon::createFromFormat('Y-m-d', $request->startDate)->addDays(-1);
        $endDate = Carbon::createFromFormat('Y-m-d', $request->endDate);
        $list = Invoice::join('users', 'users.id', '=', 'sales_id')
            ->whereBetween('date', [$startDate, $endDate])
            ->where(['group_code_id' => 'SJ'])
            ->groupBy('users.name')
            ->selectRaw('sum(amount_tax) as sumOfTotaltax, sales_id, users.name')
            ->get();
        return response()->json($list);
    }

    public function outOfStock()
    {
        $stock = Invoice::leftJoin('products', 'products.id', '=', 'product_id')
            ->leftJoin('locations', 'locations.id', '=', 'location_id')
            ->groupBy('product_id', 'location_id')
            ->selectRaw('
            sum(ndc * qty * ratio) as Stock,
            concat(ifnull(truncate((sum(ndc * qty * ratio) / products.ratio1),0),0),"|",
                ifnull(truncate((sum(ndc * qty * ratio) - (truncate((sum(ndc * qty * ratio) / products.ratio1),0) * products.ratio1)) / products.ratio2,0),0),"|",
                ifnull(truncate((sum(ndc * qty * ratio) - ((truncate((sum(ndc * qty * ratio) / products.ratio1),0) * products.ratio1) + (truncate((sum(ndc * qty * ratio) - (truncate((sum(ndc * qty * ratio) / products.ratio1),0) * products.ratio1)) / products.ratio2,0) * products.ratio2))) / products.ratio3,0),0),"|",
                ifnull(truncate((sum(ndc * qty * ratio) - ((truncate((sum(ndc * qty * ratio) / products.ratio1),0) * products.ratio1) + (truncate((sum(ndc * qty * ratio) - (truncate((sum(ndc * qty * ratio) / products.ratio1),0) * products.ratio1)) / products.ratio2,0) * products.ratio2) + (truncate((sum(ndc * qty * ratio) - ((truncate((sum(ndc * qty * ratio) / products.ratio1),0) * products.ratio1) + (truncate((sum(ndc * qty * ratio) - (truncate((sum(ndc * qty * ratio) / products.ratio1),0) * products.ratio1)) / products.ratio2,0) * products.ratio2))) / products.ratio3,0)) * products.ratio3)) / products.ratio4,0),0)) as Qty,
            product_id,
            products.name as productName,
            locations.name as locationName')
            ->where('location_id', 1)
            ->get();
        $list = Invoice::groupBy('product_id', 'location_id')
            ->selectRaw('sum(ndc * qty * ratio) as sum, product_id, location_id')
            ->where('location_id', 1)
            ->havingRaw('sum < 1')
            ->pluck('product_id');
        $transactionDetail = Product::WhereIn('id', $list)
            ->orderBy('products.name')
            ->get(['id', 'name']);
        $c = $transactionDetail->map(function ($row) use ($stock) {
            $stock = $stock->where('product_id', $row->id)->pluck('Qty')->first();
            return collect($row)->put('Stock', $stock);
        });
        return response()->json($c);
    }

    public function salesReturn(Request $request)
    {
        $startDate = Carbon::createFromFormat('Y-m-d', $request->startDate)->addDays(-1);
        $endDate = Carbon::createFromFormat('Y-m-d', $request->endDate);
        $list = Invoice::with('status_return:id,name', 'contact:id,name', 'product:id,name', 'user:id,name')
            ->where('group_code_id', 'SR')
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('contact_id, status_return_id, product_id, sales_id, amount_tax')
            ->get();
        return response()->json($list);
    }
}
