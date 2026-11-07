<?php

namespace App\Http\Controllers;

use App\Models\Chamber;
use App\Models\Order;
use App\Models\Product;
use App\Models\SensorReading;
use App\Models\VitalityLog;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Module 1: Technical IoT Incubation Dashboard (Owner Only)
     */
    public function dashboard(Request $request)
    {
        $chamber = Chamber::first();
        if (!$chamber) {
            $chamber = Chamber::create([
                'name' => 'Incubator Alpha',
                'location' => 'Roxas (Main Hatchery)',
                'setpoint' => 37.50,
                'critical_temp' => 38.50,
                'heater_state' => true,
                'fan_pwm' => 50,
                'mode' => 'AUTO',
                'batch_name' => 'BATCH-2026-D01',
                'batch_day' => 14,
                'duck_breed' => 'Pateros Mallard (Itik Pinas)',
                'egg_count' => 120,
            ]);
        }

        $latestReading = SensorReading::where('chamber_id', $chamber->id)->latest('id')->first();
        $vitalityLogs = VitalityLog::where('chamber_id', $chamber->id)->latest()->limit(10)->get();
        $vitalityTotal = VitalityLog::where('chamber_id', $chamber->id)->count();
        $vitalityViable = VitalityLog::where('chamber_id', $chamber->id)->where('result', 'viable')->count();
        $viabilityRate = $vitalityTotal > 0 ? round(($vitalityViable / $vitalityTotal) * 100, 1) : 0;

        return view('admin.dashboard', compact(
            'chamber',
            'latestReading',
            'vitalityLogs',
            'vitalityTotal',
            'vitalityViable',
            'viabilityRate'
        ));
    }

    /**
     * Module 3: Inventory Management view (Owner Only)
     */
    public function inventory(Request $request)
    {
        $productsRoxas = Product::where('branch_location', 'Roxas')
            ->orderByRaw("CASE size WHEN 'Large' THEN 1 WHEN 'Medium' THEN 2 WHEN 'Small' THEN 3 ELSE 4 END")
            ->get();

        $productsBoracay = Product::where('branch_location', 'Boracay')
            ->orderByRaw("CASE size WHEN 'Large' THEN 1 WHEN 'Medium' THEN 2 WHEN 'Small' THEN 3 ELSE 4 END")
            ->get();

        return view('admin.inventory', compact('productsRoxas', 'productsBoracay'));
    }

    /**
     * Update stock and price for a product.
     * POST /admin/inventory/update
     */
    public function updateInventory(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $product->update([
            'price' => $validated['price'],
            'stock' => $validated['stock'],
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$product->size} ({$product->branch_location}) updated successfully.",
                'product' => $product,
            ]);
        }

        return redirect()->back()->with('success', "Updated {$product->size} duck egg inventory for {$product->branch_location} branch.");
    }

    /**
     * Bulk update inventory.
     * POST /admin/inventory/bulk-update
     */
    public function bulkUpdateInventory(Request $request)
    {
        $validated = $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.stock' => 'required|integer|min:0',
        ]);

        foreach ($validated['products'] as $item) {
            Product::where('id', $item['id'])->update([
                'price' => $item['price'],
                'stock' => $item['stock'],
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'All product prices and stock levels updated successfully.',
            ]);
        }

        return redirect()->back()->with('success', 'Inventory updated successfully for all branches.');
    }

    /**
     * Module 3: Order Fulfillment Board (Kanban-style) (Owner Only)
     */
    public function orders(Request $request)
    {
        $branchFilter = $request->input('branch', 'all');

        $query = Order::with('items')->latest();
        if ($branchFilter !== 'all' && in_array($branchFilter, ['Roxas', 'Boracay'])) {
            $query->where('branch_location', $branchFilter);
        }

        $allOrders = $query->get();

        $kanban = [
            'Pending' => $allOrders->where('status', 'Pending')->values(),
            'Processing' => $allOrders->where('status', 'Processing')->values(),
            'Shipped' => $allOrders->where('status', 'Shipped')->values(),
            'Delivered' => $allOrders->where('status', 'Delivered')->values(),
        ];

        $counts = [
            'total' => $allOrders->count(),
            'pending' => $kanban['Pending']->count(),
            'processing' => $kanban['Processing']->count(),
            'shipped' => $kanban['Shipped']->count(),
            'delivered' => $kanban['Delivered']->count(),
            'total_revenue' => $allOrders->sum('total_amount'),
        ];

        return view('admin.orders', compact('kanban', 'counts', 'branchFilter'));
    }

    /**
     * Update order status on Kanban board.
     * POST /admin/orders/{id}/status
     */
    public function updateOrderStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,Processing,Shipped,Delivered',
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $validated['status']]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Order {$order->order_number} moved to {$order->status}.",
                'order' => $order->load('items'),
            ]);
        }

        return redirect()->back()->with('success', "Order {$order->order_number} marked as {$order->status}.");
    }
}
