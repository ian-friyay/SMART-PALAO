<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    /**
     * Storefront home page.
     */
    public function index(Request $request)
    {
        $selectedBranch = $request->session()->get('selected_branch', 'Gloria');
        $products = Product::where('branch_location', $selectedBranch)
            ->orderByRaw("CASE size WHEN 'Large' THEN 1 WHEN 'Medium' THEN 2 WHEN 'Small' THEN 3 ELSE 4 END")
            ->get();

        return view('store.index', compact('products', 'selectedBranch'));
    }

    /**
     * About Us page highlighting farm heritage and IoT incubation technology.
     */
    public function about()
    {
        return view('store.about');
    }

    /**
     * Cart and Checkout page.
     */
    public function cart(Request $request)
    {
        $selectedBranch = $request->session()->get('selected_branch', 'Gloria');
        $products = Product::where('branch_location', $selectedBranch)->get();

        return view('store.cart', compact('selectedBranch', 'products'));
    }

    /**
     * Set active branch in session.
     */
    public function setBranch(Request $request)
    {
        $validated = $request->validate([
            'branch' => 'required|in:Gloria,Boracay',
        ]);

        $request->session()->put('selected_branch', $validated['branch']);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'branch' => $validated['branch'],
                'message' => "Fulfillment branch set to {$validated['branch']}.",
            ]);
        }

        return redirect()->back()->with('success', "Branch changed to {$validated['branch']}. Inventory updated.");
    }

    /**
     * API: Get products filtered by branch.
     * GET /api/store/products
     */
    public function getProducts(Request $request)
    {
        $branch = $request->input('branch', $request->session()->get('selected_branch', 'Gloria'));
        
        $products = Product::where('branch_location', $branch)
            ->orderByRaw("CASE size WHEN 'Large' THEN 1 WHEN 'Medium' THEN 2 WHEN 'Small' THEN 3 ELSE 4 END")
            ->get();

        return response()->json([
            'success' => true,
            'branch' => $branch,
            'products' => $products,
        ]);
    }

    /**
     * Process checkout and create order.
     * POST /store/checkout or POST /api/store/checkout
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'branch_location' => 'required|in:Gloria,Boracay',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:25',
            'delivery_address' => 'required|string|max:1000',
            'payment_method' => 'required|in:Cash on Delivery,GCash,Bank Transfer',
            'notes' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            $branch = $validated['branch_location'];
            $itemsData = [];
            $totalAmount = 0;

            foreach ($validated['items'] as $itemInput) {
                $product = Product::lockForUpdate()->findOrFail($itemInput['product_id']);

                if ($product->stock < $itemInput['quantity']) {
                    $msg = "Insufficient stock for {$product->size} Duck Eggs at {$branch} branch. Available: {$product->stock}.";
                    if ($request->wantsJson()) {
                        return response()->json(['success' => false, 'message' => $msg], 422);
                    }
                    return back()->with('error', $msg);
                }

                // Decrement inventory stock
                $product->decrement('stock', $itemInput['quantity']);

                $subtotal = $product->price * $itemInput['quantity'];
                $totalAmount += $subtotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'size' => $product->size,
                    'unit_price' => $product->price,
                    'quantity' => $itemInput['quantity'],
                    'subtotal' => $subtotal,
                ];
            }

            // Generate unique Order Number e.g. SP-GLO-2026-9821
            $prefix = $branch === 'Gloria' ? 'GLO' : 'BOR';
            $orderNumber = 'SP-' . $prefix . '-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => Auth::id(), // null if guest or logged in buyer
                'branch_location' => $branch,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'delivery_address' => $validated['delivery_address'],
                'payment_method' => $validated['payment_method'],
                'status' => 'Pending',
                'total_amount' => $totalAmount,
                'delivery_fee' => $branch === 'Boracay' ? 75.00 : 50.00,
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($itemsData as $item) {
                $order->items()->create($item);
            }

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order placed successfully!',
                    'order_number' => $order->order_number,
                    'order' => $order->load('items'),
                ], 201);
            }

            return redirect()->route('store.confirmation', ['orderNumber' => $order->order_number])
                ->with('success', 'Order placed successfully!');
        });
    }

    /**
     * Order confirmation view.
     */
    public function confirmation($orderNumber)
    {
        $order = Order::with('items')->where('order_number', $orderNumber)->firstOrFail();

        return view('store.confirmation', compact('order'));
    }

    /**
     * Buyer Order History view.
     */
    public function buyerOrders(Request $request)
    {
        $user = Auth::user();
        $orders = Order::with('items')
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('customer_email', $user->email);
            })
            ->latest()
            ->paginate(10);

        return view('store.orders', compact('orders'));
    }
}
