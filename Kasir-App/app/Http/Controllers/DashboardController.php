<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return $this->adminDashboard();
        }

        return $this->employeeDashboard();
    }

    private function adminDashboard()
    {
        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_products' => \App\Models\Product::count(),
            'total_orders' => \App\Models\Order::count(),
            'total_customers' => \App\Models\Customer::count(),
            'total_revenue' => \App\Models\Order::sum('total_price'),
            'today_orders' => \App\Models\Order::whereDate('sale_date', today())->count(),
            'today_revenue' => \App\Models\Order::whereDate('sale_date', today())->sum('total_price'),
            'low_stock_products' => \App\Models\Product::where('stock', '<=', 10)->count(),
        ];

        $recentOrders = \App\Models\Order::with(['customer', 'employee'])
            ->orderBy('sale_date', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }

    private function employeeDashboard()
    {
        $employeeId = Auth::id();

        $stats = [
            'my_orders' => \App\Models\Order::where('employee_id', $employeeId)->count(),
            'my_revenue' => \App\Models\Order::where('employee_id', $employeeId)->sum('total_price'),
            'today_orders' => \App\Models\Order::where('employee_id', $employeeId)
                ->whereDate('sale_date', today())->count(),
            'today_revenue' => \App\Models\Order::where('employee_id', $employeeId)
                ->whereDate('sale_date', today())->sum('total_price'),
        ];

        $recentOrders = \App\Models\Order::with(['customer'])
            ->where('employee_id', $employeeId)
            ->orderBy('sale_date', 'desc')
            ->take(5)
            ->get();

        return view('employee.dashboard', compact('stats', 'recentOrders'));
    }
}
