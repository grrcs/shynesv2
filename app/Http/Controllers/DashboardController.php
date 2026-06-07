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
        $totalRevenue = Order::sum('total_price');
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalUsers = User::count();
        
        $recentOrders = Order::with('user')->latest()->take(5)->get();
        $lowStockProducts = Product::where('stock', '<', 5)->get();

        // POS Sales Report
        $todayOrders = Order::where('status', 'completed')->whereDate('created_at', today())->get();
        $todayRevenue = $todayOrders->sum('total_price');
        $todayOrderCount = $todayOrders->count();

        // Weekly data for chart
        $weeklyData = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $orders = Order::where('status', 'completed')
                ->whereDate('created_at', $date->format('Y-m-d'))
                ->get();
            
            $weeklyData->push([
                'date' => $date->format('d/m'),
                'day' => $date->format('D'),
                'revenue' => $orders->sum('total_price'),
                'orders' => $orders->count()
            ]);
        }

        $weeklyRevenue = $weeklyData->sum('revenue');
        $weeklyOrderCount = $weeklyData->sum('orders');

        return view('admin.dashboard.index', compact(
            'totalRevenue', 
            'totalOrders', 
            'totalProducts', 
            'totalUsers',
            'recentOrders',
            'lowStockProducts',
            'todayRevenue',
            'todayOrderCount',
            'weeklyData',
            'weeklyRevenue',
            'weeklyOrderCount'
        ));
    }
}
