<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockTransactionController extends Controller
{
    public function index()
    {
        $transactions = StockTransaction::with(['product', 'user'])
            ->latest()
            ->paginate(15);

        return view('stock_transactions.index', compact('transactions'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('stock_transactions.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type'       => 'required|in:in,out',
            'quantity'   => 'required|integer|min:1',
            'note'       => 'nullable|string|max:500',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Stock out ke liye check karo — sufficient stock hai?
        if ($request->type === 'out' && $product->quantity < $request->quantity) {
            return back()
                ->withInput()
                ->withErrors(['quantity' => "Insufficient stock. Available: {$product->quantity}"]);
        }

        // Atomic operation — dono ek saath hone chahiye
        DB::transaction(function () use ($request, $product) {

            // Transaction log karo
            StockTransaction::create([
                'product_id' => $request->product_id,
                'user_id'    => auth()->id(),
                'type'       => $request->type,
                'quantity'   => $request->quantity,
                'note'       => $request->note,
            ]);

            // Product quantity update karo
            if ($request->type === 'in') {
                $product->increment('quantity', $request->quantity);
            } else {
                $product->decrement('quantity', $request->quantity);
            }
        });

        return redirect()->route('stock-transactions.index')
            ->with('success', 'Stock updated successfully.');
    }
}
