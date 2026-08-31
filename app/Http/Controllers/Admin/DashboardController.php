<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Model\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $paidStatuses = ['paid', 'processing', 'shipped', 'delivered'];

        $stats = [
            'total_revenue' => Oder::whereIn('status', $paidStatuses)->sum('total'),
            'total_orders' => Order::whereIn('status', $paidStatuses)->count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'low_stock_count' => Product::where('stock', '<=', 5)->count(),
        ];

        $revenueByDay = Order::whereIn('status', $paidStatuses)
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $revenueChart = collect(range(0, 29))->map(function ($i) use ($revenueByDay){
            $date = now()->subDays(29 - $i)->format(Y-m-d);
            $match = $revenueByDay->firstWhere('date', $date);
            return [
                'date' => now()->subDays(29 - $i)->format('M d'),
                'total' => $match ? (float) $match->total : 0,
            ];
        });

        $topProducts = OrderItem::select('product_name')
            ->selectRaw('SUM(quantity) as total_sold')
            ->selectRaw('SUM(price * quantity) as total_revenue')
            ->whereHas('order', fn ($q) => $q->whereIn('status', $paidStatuses))
            ->groupBy('product_name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        $statusBreakdown = Order::select('status')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('status')
            ->get();

        return view('admin.dashboard', compact('stats', 'revenueChart', 'topProducts', 'statusBreakdown'));
    }
}
