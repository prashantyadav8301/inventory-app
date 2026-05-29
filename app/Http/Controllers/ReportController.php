<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'from' => 'nullable|date',
            'to'   => 'nullable|date|after_or_equal:from',
        ]);

        $query = StockTransaction::with(['product', 'user']);

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $transactions = $query->latest()->get();

        // Summary
        $totalIn  = $transactions->where('type', 'in')->sum('quantity');
        $totalOut = $transactions->where('type', 'out')->sum('quantity');

        // Low stock products
        $lowStockProducts = Product::whereColumn('quantity', '<=', 'min_threshold')->get();

        return view('reports.index', compact(
            'transactions',
            'totalIn',
            'totalOut',
            'lowStockProducts',
            'request'
        ));
    }

    public function exportCsv(Request $request)
    {
        $request->validate([
            'from' => 'nullable|date',
            'to'   => 'nullable|date|after_or_equal:from',
        ]);

        $query = StockTransaction::with(['product', 'user']);

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $transactions = $query->latest()->get();

        $filename = 'stock_report_' . now()->format('Y_m_d_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($transactions) {
            $handle = fopen('php://output', 'w');

            // CSV Header row
            fputcsv($handle, [
                'Date',
                'Product',
                'SKU',
                'Type',
                'Quantity',
                'Note',
                'Done By',
            ]);

            // Data rows
            foreach ($transactions as $transaction) {
                fputcsv($handle, [
                    $transaction->created_at->format('d M Y h:i A'),
                    $transaction->product->name,
                    $transaction->product->sku,
                    strtoupper($transaction->type),
                    $transaction->quantity,
                    $transaction->note ?? '',
                    $transaction->user->name,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportStockCsv()
    {
        $products = Product::with(['category', 'supplier'])->get();

        $filename = 'stock_summary_' . now()->format('Y_m_d_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($products) {
            $handle = fopen('php://output', 'w');

            // CSV Header row
            fputcsv($handle, [
                'Product Name',
                'SKU',
                'Category',
                'Supplier',
                'Price',
                'Current Stock',
                'Min Threshold',
                'Status',
            ]);

            // Data rows
            foreach ($products as $product) {
                fputcsv($handle, [
                    $product->name,
                    $product->sku,
                    $product->category->name,
                    $product->supplier->name,
                    $product->price,
                    $product->quantity,
                    $product->min_threshold,
                    $product->isLowStock() ? 'LOW STOCK' : 'OK',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
