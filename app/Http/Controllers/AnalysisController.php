<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnalysisController extends Controller
{
    public function index()
    {
        $startDate = '2022-08-01';
        $endDate = '2022-08-10';
        $period = Order::betweenDate($startDate, $endDate)
            ->groupBy('id')
            ->selectRaw('id, sum(subtotal) as total,
                        customer_name, status, created_at')
            ->orderBy('created_at')
            ->paginate(50);

        return Inertia::render('Analysis');
    }
}
