<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    public function index(Request $request)
    {

        $subQuery = Order::betweenDate($request->startDate, $request->endDate)
            ->where('status', true)
            ->groupBy('id')
            ->selectRaw('id, sum(subtotal) as totalPerPurchase,
                        DATE_FORMAT(created_at, "%Y%m%d") as date');
        $data = [];
        if ($request->type == "perDay") {
            $data = DB::table($subQuery)->groupBy('date')
                ->selectRaw('date, sum(totalPerPurchase) as total')
                ->get();
            $labels = $data->pluck('date');
            $totals = $data->pluck("total");
        }


        return response()->json(
            [
                'data' => $data,
                'labels' => $labels,
                'totals' => $totals,
                'type' => $request->type,
            ], Response::HTTP_OK
        );
    }
}
