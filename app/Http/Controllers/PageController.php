<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class PageController extends Controller
{
    public function index()
    {
        $users = User::all();
        $products = Product::all();
        $orders = Order::orderBy('id', 'desc')->paginate(10);
        return view('page.orders', compact('products', 'users', 'orders'));
    }

    public function report()
    {
        return view('page.report');
    }
}
