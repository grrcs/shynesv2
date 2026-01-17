<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalRevenue = Order::sum('total_price'); // Assuming total_price exists in orders
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalUsers = User::count();
        
        $recentOrders = Order::with('user')->latest()->take(5)->get();
        $lowStockProducts = Product::where('stock', '<', 5)->get();

        return view('admin.dashboard.index', compact(
            'totalRevenue', 
            'totalOrders', 
            'totalProducts', 
            'totalUsers',
            'recentOrders',
            'lowStockProducts'
        ));
    }
}
