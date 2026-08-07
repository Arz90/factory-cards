<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders'    => Order::count(),
            'paid_orders'     => Order::where('status', 'paid')->count(),
            'revenue_month'   => Order::where('status', 'paid')
                ->whereMonth('paid_at', now()->month)
                ->whereYear('paid_at', now()->year)
                ->sum('total'),
            'revenue_total'   => Order::where('status', 'paid')->sum('total'),
            'total_products'  => Product::count(),
            'active_products' => Product::where('status', 'active')->count(),
            'low_stock'       => Product::where('stock', '>', 0)->where('stock', '<=', 5)->count(),
            'out_of_stock'    => Product::where('stock', 0)->count(),
            'total_customers' => User::where('role', 'customer')->count(),
        ];

        $recent_orders = Order::with('user')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $low_stock_products = Product::where('stock', '<=', 5)
            ->orderBy('stock')
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_orders', 'low_stock_products'));
    }
}
