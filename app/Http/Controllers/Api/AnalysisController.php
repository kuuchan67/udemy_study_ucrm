<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\AnalysisService;
use App\Services\DecileService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    public function index(Request $request)
    {

        $subQuery = Order::betweenDate($request->startDate, $request->endDate);

        $data = [];
        $labels = [];
        $totals = [];
        if ($request->type == "perDay") {
           list($data, $labels, $totals) = AnalysisService::perDay($subQuery);
        }

        if ($request->type == "perMonth") {
            list($data, $labels, $totals) = AnalysisService::perMonth($subQuery);
        }

        if ($request->type == "perYear") {
            list($data, $labels, $totals) = AnalysisService::perYear($subQuery);
        }

        if ($request->type == "decile") {
            list($data, $labels, $totals) =DecileService::decile($subQuery);
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
