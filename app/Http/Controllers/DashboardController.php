<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\Category;
use App\Models\Supplier;

class DashboardController extends Controller
{
    public function index()
    {
        // Summary cards
        $totalProducts     = Product::count();
        $totalCategories   = Category::count();
        $totalSuppliers    = Supplier::count();
        $lowStockProducts  = Product::whereColumn('quantity', '<=', 'min_threshold')->get();
        $lowStockCount     = $lowStockProducts->count();

        // Recent 5 transactions
        $recentTransactions = StockTransaction::with(['product', 'user'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalSuppliers',
            'lowStockCount',
            'lowStockProducts',
            'recentTransactions'
        ));
    }
}
