@extends('layouts.app')

@section('title', 'My Orders - Smart Palao')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">

    <div class="flex items-center justify-between pb-6 border-b border-[#D9CBB8] dark:border-[#3A3833]">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-[#FFC107]">Customer Account</span>
            <h1 class="text-3xl font-black text-[#3A3A3A] dark:text-[#F2EFE9] tracking-tight mt-0.5">My Order History</h1>
            <p class="text-xs text-[#7A7A7A] dark:text-[#8E8B83] mt-1">Track fulfillment and past shipments of Smart Palao duck eggs</p>
        </div>
        <a href="{{ route('store') }}" class="egg-btn-primary px-4 py-2 rounded-xl text-xs font-bold">
            + Order More Eggs
        </a>
    </div>

    <!-- Orders List -->
    <div class="space-y-4">
        @forelse($orders as $order)
            <div class="egg-card p-6 bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] rounded-3xl shadow-sm hover:border-[#FFC107] transition space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-[#D9CBB8]/40 dark:border-[#3A3833]">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#FFF8E1] dark:bg-[#2A261A] border border-[#FFC107] flex items-center justify-center text-xl">
                            🥚
                        </div>
                        <div>
                            <span class="font-mono font-black text-sm text-[#3A3A3A] dark:text-[#F2EFE9]">{{ $order->order_number }}</span>
                            <span class="text-xs text-gray-400 dark:text-gray-500 block">{{ $order->created_at->format('M d, Y • h:i A') }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-xs text-[#89B4C4] font-bold">📍 {{ $order->branch_location }} Hub</span>
                        @php
                            $badgeClass = match($order->status) {
                                'Pending' => 'bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-800',
                                'Processing' => 'bg-blue-100 dark:bg-blue-950/60 text-blue-800 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                                'Shipped' => 'bg-purple-100 dark:bg-purple-950/60 text-purple-800 dark:text-purple-300 border-purple-200 dark:border-purple-800',
                                'Delivered' => 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                                default => 'bg-gray-100 text-gray-800',
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-black uppercase border {{ $badgeClass }}">
                            {{ $order->status }}
                        </span>
                    </div>
                </div>

                <!-- Items Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 text-xs">
                    @foreach($order->items as $item)
                        <div class="p-3 rounded-2xl bg-[#FAF9F6] dark:bg-[#1A1917] border border-[#D9CBB8] dark:border-[#3A3833] flex items-center justify-between">
                            <div>
                                <span class="font-extrabold text-[#3A3A3A] dark:text-[#F2EFE9] block">{{ $item->quantity }}x {{ $item->size }} Duck Eggs</span>
                                <span class="text-[10px] text-gray-500 dark:text-gray-400 font-mono">₱{{ number_format($item->unit_price, 2) }} each</span>
                            </div>
                            <span class="font-mono font-bold text-[#3A3A3A] dark:text-[#F2EFE9]">₱{{ number_format($item->subtotal, 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <!-- Footer / Cost -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-3 border-t border-[#D9CBB8]/40 dark:border-[#3A3833] text-xs text-gray-600 dark:text-gray-400">
                    <div>
                        <span>Payment: <strong class="text-[#3A3A3A] dark:text-[#F2EFE9]">{{ $order->payment_method }}</strong></span>
                        <span class="mx-2">•</span>
                        <span>Delivering to: <span class="text-gray-500 dark:text-gray-400">{{ Str::limit($order->delivery_address, 40) }}</span></span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-gray-500 dark:text-gray-400">Total (incl. fee):</span>
                        <span class="text-lg font-black font-mono text-[#3A3A3A] dark:text-[#F2EFE9] ml-1">
                            ₱{{ number_format($order->total_amount + $order->delivery_fee, 2) }}
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="egg-card p-12 bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] rounded-3xl text-center space-y-3">
                <span class="text-4xl">🧺</span>
                <h3 class="font-bold text-base text-[#3A3A3A] dark:text-[#F2EFE9]">No past orders found</h3>
                <p class="text-xs text-[#7A7A7A] dark:text-[#8E8B83]">You haven't placed an order with Smart Palao yet.</p>
                <div class="pt-2">
                    <a href="{{ route('store') }}" class="egg-btn-primary px-5 py-2 rounded-xl text-xs font-bold inline-block">
                        Order Fresh Duck Eggs Now
                    </a>
                </div>
            </div>
        @endforelse

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>

</div>
@endsection
