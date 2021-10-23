<?php

namespace App\Http\Controllers;

use App\Models\Order;

use Illuminate\Http\Request;
use App\Helpers\Order\OrderHelper;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $isSelectedDate = $request->has('date_completed');
        $date = ($isSelectedDate) ? $request->date_completed : date('Y-m-d');
        $orders = Order::whereDate('completed_at', $date)->with('products')->get();
        $countOrders = 0;
        $totalFormat = 0;
        if ($orders->isNotEmpty()) {
            $countOrders = $orders->count();
            $total = OrderHelper::getTotalPriceProducts($orders);
            $totalFormat = number_format($total, 0, '.', ' ');
        }
        return view('page.report', compact('isSelectedDate', 'date',
            'countOrders', 'totalFormat'));
    }
}
