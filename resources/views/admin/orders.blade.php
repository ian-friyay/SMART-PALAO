@extends('layouts.app')

@section('title', 'Order Fulfillment Kanban - Smart Palao (Owner Only)')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-[#D9CBB8] dark:border-[#3A3833]">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-widest text-[#FFC107]">Module 3 • Order Management</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-[#FFC107] text-[#1F1F1F]">Owner Only</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-[#3A3A3A] dark:text-[#F2EFE9] tracking-tight mt-1">Order Fulfillment Kanban Board</h1>
            <p class="text-xs text-[#7A7A7A] dark:text-[#8E8B83] mt-0.5">
                Dispatch duck egg trays across branch locations (Pending &rarr; Processing &rarr; Shipped &rarr; Delivered)
            </p>
        </div>

        <!-- Branch Filter Tabs -->
        <div class="flex items-center gap-1.5 sm:gap-2 bg-white dark:bg-[#22201D] p-1 rounded-2xl border border-[#D9CBB8] dark:border-[#3A3833] shadow-sm w-full sm:w-auto overflow-x-auto">
            <a href="{{ route('admin.orders', ['branch' => 'all']) }}" class="flex-1 sm:flex-initial text-center px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $branchFilter === 'all' ? 'bg-[#FFC107] text-[#1F1F1F] shadow' : 'text-[#5A5A5A] dark:text-[#A8A49C] hover:bg-[#FAF8F5] dark:hover:bg-[#2A2824]' }}">
                All ({{ $counts['total'] }})
            </a>
            <a href="{{ route('admin.orders', ['branch' => 'Roxas']) }}" class="flex-1 sm:flex-initial text-center px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $branchFilter === 'Roxas' ? 'bg-[#FFC107] text-[#1F1F1F] shadow' : 'text-[#5A5A5A] dark:text-[#A8A49C] hover:bg-[#FAF8F5] dark:hover:bg-[#2A2824]' }}">
                🌾 Roxas
            </a>
            <a href="{{ route('admin.orders', ['branch' => 'Boracay']) }}" class="flex-1 sm:flex-initial text-center px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $branchFilter === 'Boracay' ? 'bg-[#89B4C4] text-white shadow' : 'text-[#5A5A5A] dark:text-[#A8A49C] hover:bg-[#FAF8F5] dark:hover:bg-[#2A2824]' }}">
                🏖️ Boracay
            </a>
        </div>
    </div>

    <!-- Metrics Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2.5 sm:gap-3">
        <div class="egg-card p-3 sm:p-4 bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] rounded-2xl text-center">
            <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase block">Pending</span>
            <span class="text-xl sm:text-2xl font-black font-mono text-amber-600 dark:text-amber-400">{{ $counts['pending'] }}</span>
        </div>
        <div class="egg-card p-3 sm:p-4 bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] rounded-2xl text-center">
            <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase block">Processing</span>
            <span class="text-xl sm:text-2xl font-black font-mono text-[#89B4C4]">{{ $counts['processing'] }}</span>
        </div>
        <div class="egg-card p-3 sm:p-4 bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] rounded-2xl text-center">
            <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase block">In Transit</span>
            <span class="text-xl sm:text-2xl font-black font-mono text-purple-600 dark:text-purple-400">{{ $counts['shipped'] }}</span>
        </div>
        <div class="egg-card p-3 sm:p-4 bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] rounded-2xl text-center">
            <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase block">Delivered</span>
            <span class="text-xl sm:text-2xl font-black font-mono text-emerald-600 dark:text-emerald-400">{{ $counts['delivered'] }}</span>
        </div>
        <div class="egg-card p-3 sm:p-4 bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] rounded-2xl text-center col-span-2 sm:col-span-3 lg:col-span-1">
            <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase block">Gross Sales</span>
            <span class="text-lg sm:text-xl font-black font-mono text-[#3A3A3A] dark:text-[#F2EFE9]">₱{{ number_format($counts['total_revenue'], 2) }}</span>
        </div>
    </div>

    <!-- 4-Column Kanban Board -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-start">

        <!-- Column 1: Pending -->
        <div class="rounded-3xl bg-[#FAF9F6] dark:bg-[#1A1917] border-2 border-dashed border-amber-300 dark:border-amber-700/60 p-4 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-amber-200 dark:border-amber-800/40">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                    <h3 class="font-extrabold text-sm text-[#3A3A3A] dark:text-[#F2EFE9]">1. Pending</h3>
                </div>
                <span class="px-2 py-0.5 rounded-full text-xs font-black bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300">
                    {{ $kanban['Pending']->count() }}
                </span>
            </div>

            <div class="space-y-3">
                @forelse($kanban['Pending'] as $order)
                    @include('admin.partials.kanban-card', ['order' => $order, 'stage' => 'Pending'])
                @empty
                    <div class="text-center py-8 text-xs text-gray-400 dark:text-gray-500 font-medium">No pending orders.</div>
                @endforelse
            </div>
        </div>

        <!-- Column 2: Processing -->
        <div class="rounded-3xl bg-[#FAF9F6] dark:bg-[#1A1917] border-2 border-dashed border-[#89B4C4] dark:border-[#4B7382] p-4 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-[#89B4C4]/50 dark:border-[#4B7382]/50">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-[#89B4C4]"></span>
                    <h3 class="font-extrabold text-sm text-[#3A3A3A] dark:text-[#F2EFE9]">2. Processing</h3>
                </div>
                <span class="px-2 py-0.5 rounded-full text-xs font-black bg-[#89B4C4]/20 text-[#3D6677] dark:text-[#89B4C4]">
                    {{ $kanban['Processing']->count() }}
                </span>
            </div>

            <div class="space-y-3">
                @forelse($kanban['Processing'] as $order)
                    @include('admin.partials.kanban-card', ['order' => $order, 'stage' => 'Processing'])
                @empty
                    <div class="text-center py-8 text-xs text-gray-400 dark:text-gray-500 font-medium">No orders in processing.</div>
                @endforelse
            </div>
        </div>

        <!-- Column 3: Shipped -->
        <div class="rounded-3xl bg-[#FAF9F6] dark:bg-[#1A1917] border-2 border-dashed border-purple-300 dark:border-purple-800/60 p-4 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-purple-200 dark:border-purple-800/40">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-purple-500"></span>
                    <h3 class="font-extrabold text-sm text-[#3A3A3A] dark:text-[#F2EFE9]">3. Shipped</h3>
                </div>
                <span class="px-2 py-0.5 rounded-full text-xs font-black bg-purple-100 dark:bg-purple-950/60 text-purple-800 dark:text-purple-300">
                    {{ $kanban['Shipped']->count() }}
                </span>
            </div>

            <div class="space-y-3">
                @forelse($kanban['Shipped'] as $order)
                    @include('admin.partials.kanban-card', ['order' => $order, 'stage' => 'Shipped'])
                @empty
                    <div class="text-center py-8 text-xs text-gray-400 dark:text-gray-500 font-medium">No orders in transit.</div>
                @endforelse
            </div>
        </div>

        <!-- Column 4: Delivered -->
        <div class="rounded-3xl bg-[#FAF9F6] dark:bg-[#1A1917] border-2 border-dashed border-emerald-300 dark:border-emerald-800/60 p-4 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-emerald-200 dark:border-emerald-800/40">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    <h3 class="font-extrabold text-sm text-[#3A3A3A] dark:text-[#F2EFE9]">4. Delivered</h3>
                </div>
                <span class="px-2 py-0.5 rounded-full text-xs font-black bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300">
                    {{ $kanban['Delivered']->count() }}
                </span>
            </div>

            <div class="space-y-3">
                @forelse($kanban['Delivered'] as $order)
                    @include('admin.partials.kanban-card', ['order' => $order, 'stage' => 'Delivered'])
                @empty
                    <div class="text-center py-8 text-xs text-gray-400 dark:text-gray-500 font-medium">No delivered orders yet.</div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
