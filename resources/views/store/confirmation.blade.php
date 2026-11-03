@extends('layouts.app')

@section('title', 'Order Confirmed - Smart Palao')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12 space-y-8">

    <div class="egg-card p-8 md:p-12 bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] rounded-3xl shadow-lg text-center space-y-6">
        <!-- Success Icon / Logo -->
        <div class="w-20 h-20 rounded-full bg-[#FFF8E1] dark:bg-[#2A261A] text-[#FFC107] mx-auto flex items-center justify-center p-2 border-2 border-[#FFC107] shadow-sm animate-bounce">
            <img src="{{ asset('images/smart-palao-logo.png') }}" alt="Success" class="w-full h-full object-contain rounded-full">
        </div>

        <div>
            <span class="text-xs font-extrabold uppercase tracking-widest text-[#FFC107]">Order Placed Successfully</span>
            <h1 class="text-3xl font-black text-[#3A3A3A] dark:text-[#F2EFE9] tracking-tight mt-1">Thank You For Your Order!</h1>
            <p class="text-xs text-[#7A7A7A] dark:text-[#8E8B83] mt-1">
                Your duck eggs are being selected from our <span class="font-bold text-[#3A3A3A] dark:text-[#F2EFE9]">📍 {{ $order->branch_location }}</span> incubation hub.
            </p>
        </div>

        <!-- Order Reference Badge -->
        <div class="p-4 rounded-2xl bg-[#FAF9F6] dark:bg-[#1A1917] border border-[#D9CBB8] dark:border-[#3A3833] inline-flex flex-col sm:flex-row items-center justify-between gap-4 max-w-md mx-auto w-full text-xs">
            <div class="text-left">
                <span class="text-[10px] text-gray-500 dark:text-gray-400 uppercase font-bold block">Order Reference No.</span>
                <span class="font-mono font-extrabold text-sm text-[#3A3A3A] dark:text-[#F2EFE9]">{{ $order->order_number }}</span>
            </div>
            <div class="text-right">
                <span class="text-[10px] text-gray-500 dark:text-gray-400 uppercase font-bold block">Fulfillment Status</span>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-black uppercase bg-[#FFC107] text-[#1F1F1F]">
                    {{ $order->status }}
                </span>
            </div>
        </div>

        <!-- Items Table -->
        <div class="text-left border-t border-[#D9CBB8]/50 dark:border-[#3A3833] pt-6">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#7A7A7A] dark:text-[#8E8B83] mb-3">Order Summary</h3>
            <div class="divide-y divide-gray-100 dark:divide-[#2F2D29] text-xs">
                @foreach($order->items as $item)
                    <div class="py-2.5 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span>🥚</span>
                            <span class="font-bold text-[#3A3A3A] dark:text-[#F2EFE9]">{{ $item->quantity }}x {{ $item->size }} Duck Eggs</span>
                            <span class="text-gray-400">(@ ₱{{ number_format($item->unit_price, 2) }}/doz)</span>
                        </div>
                        <span class="font-mono font-bold text-[#3A3A3A] dark:text-[#F2EFE9]">₱{{ number_format($item->subtotal, 2) }}</span>
                    </div>
                @endforeach
            </div>

            <div class="border-t border-[#D9CBB8]/40 dark:border-[#3A3833] mt-4 pt-4 space-y-1.5 text-xs">
                <div class="flex items-center justify-between text-gray-500 dark:text-gray-400">
                    <span>Subtotal:</span>
                    <span class="font-mono font-bold text-[#3A3A3A] dark:text-[#F2EFE9]">₱{{ number_format($order->total_amount, 2) }}</span>
                </div>
                <div class="flex items-center justify-between text-gray-500 dark:text-gray-400">
                    <span>Delivery Fee ({{ $order->branch_location }}):</span>
                    <span class="font-mono font-bold text-[#3A3A3A] dark:text-[#F2EFE9]">₱{{ number_format($order->delivery_fee, 2) }}</span>
                </div>
                <div class="flex items-center justify-between text-sm font-black text-[#3A3A3A] dark:text-[#F2EFE9] pt-2 border-t border-gray-200 dark:border-[#3A3833]">
                    <span>Total Amount:</span>
                    <span class="text-xl font-mono text-[#3A3A3A] dark:text-[#F2EFE9]">₱{{ number_format($order->total_amount + $order->delivery_fee, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Delivery & Payment Info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-left text-xs bg-[#FAF9F6] dark:bg-[#1A1917] p-4 rounded-2xl border border-[#D9CBB8] dark:border-[#3A3833]">
            <div>
                <span class="text-[10px] text-gray-500 dark:text-gray-400 uppercase font-bold block mb-1">Delivery Destination</span>
                <p class="font-bold text-[#3A3A3A] dark:text-[#F2EFE9]">{{ $order->customer_name }}</p>
                <p class="text-gray-600 dark:text-gray-400">{{ $order->customer_phone }}</p>
                <p class="text-gray-600 dark:text-gray-400 mt-1 leading-relaxed">{{ $order->delivery_address }}</p>
            </div>
            <div>
                <span class="text-[10px] text-gray-500 dark:text-gray-400 uppercase font-bold block mb-1">Payment & Logistics</span>
                <p class="font-bold text-[#3A3A3A] dark:text-[#F2EFE9]">Method: {{ $order->payment_method }}</p>
                <p class="text-gray-600 dark:text-gray-400">Hub: {{ $order->branch_location }} Farm Depot</p>
                @if($order->notes)
                    <p class="text-gray-500 dark:text-gray-400 text-[11px] mt-2 italic">Note: "{{ $order->notes }}"</p>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('store') }}" class="egg-btn-primary px-6 py-2.5 rounded-xl font-bold text-xs">
                Back to Egg Store
            </a>
            @auth
                <a href="{{ route('store.orders') }}" class="egg-btn-secondary px-6 py-2.5 rounded-xl font-bold text-xs">
                    View My Order History
                </a>
            @endauth
        </div>
    </div>

</div>
@endsection
