@extends('layouts.app')

@section('title', 'Egg Inventory Admin - Smart Palao (Owner Only)')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-[#D9CBB8] dark:border-[#3A3833]">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-widest text-[#FFC107]">Module 3 • E-Commerce Administration</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-[#FFC107] text-[#1F1F1F]">Owner Only</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-[#3A3A3A] dark:text-[#F2EFE9] tracking-tight mt-1">Multi-Branch Inventory Management</h1>
            <p class="text-xs text-[#7A7A7A] dark:text-[#8E8B83] mt-0.5">
                Calibrate stock allocations and pricing for Large, Medium, and Small duck eggs per fulfillment hub
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:flex sm:items-center w-full sm:w-auto">
            <a href="{{ route('admin.dashboard') }}" class="egg-btn-secondary px-3.5 py-2.5 rounded-xl text-xs font-bold text-center">
                &larr; IoT Dashboard
            </a>
            <a href="{{ route('admin.orders') }}" class="egg-btn-primary px-3.5 py-2.5 rounded-xl text-xs font-bold text-center">
                Kanban Board &rarr;
            </a>
        </div>
    </div>

    <!-- Quick Stats Bar -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="egg-card p-5 bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] rounded-3xl">
            <span class="text-[11px] font-extrabold uppercase text-[#7A7A7A] dark:text-[#8E8B83] block">Total Hatchery Stock</span>
            <div class="flex items-baseline gap-2 mt-1">
                <span class="text-3xl font-black font-mono text-[#3A3A3A] dark:text-[#F2EFE9]">
                    {{ $productsRoxas->sum('stock') + $productsBoracay->sum('stock') }}
                </span>
                <span class="text-xs text-gray-500 dark:text-gray-400 font-bold">dozens available</span>
            </div>
            <span class="text-[11px] text-emerald-600 dark:text-emerald-400 font-bold block mt-2">
                Across Roxas ({{ $productsRoxas->sum('stock') }}) & Boracay ({{ $productsBoracay->sum('stock') }})
            </span>
        </div>

        <div class="egg-card p-5 bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] rounded-3xl">
            <span class="text-[11px] font-extrabold uppercase text-[#7A7A7A] dark:text-[#8E8B83] block">Roxas Main Reserve</span>
            <div class="flex items-baseline gap-2 mt-1">
                <span class="text-3xl font-black font-mono text-[#FFC107]">
                    {{ $productsRoxas->sum('stock') }}
                </span>
                <span class="text-xs text-gray-500 dark:text-gray-400 font-bold">dozens</span>
            </div>
            <span class="text-[11px] text-gray-500 dark:text-gray-400 font-medium block mt-2">
                Main Farm & Hatchery Production Hub
            </span>
        </div>

        <div class="egg-card p-5 bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] rounded-3xl">
            <span class="text-[11px] font-extrabold uppercase text-[#7A7A7A] dark:text-[#8E8B83] block">Boracay Island Depot</span>
            <div class="flex items-baseline gap-2 mt-1">
                <span class="text-3xl font-black font-mono text-[#89B4C4]">
                    {{ $productsBoracay->sum('stock') }}
                </span>
                <span class="text-xs text-gray-500 dark:text-gray-400 font-bold">dozens</span>
            </div>
            <span class="text-[11px] text-gray-500 dark:text-gray-400 font-medium block mt-2">
                Fulfills Resort Buffet & Island Orders (Delivery Only)
            </span>
        </div>
    </div>

    <!-- Dual Branch Inventory Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- Branch 1: Roxas (Main Farm & Hatchery) -->
        <div class="egg-card p-6 bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] rounded-3xl shadow-sm space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-[#D9CBB8]/40 dark:border-[#3A3833]">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-[#FFF8E1] dark:bg-[#2A261A] border border-[#FFC107] flex items-center justify-center text-xl">
                        🌾
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-[#3A3A3A] dark:text-[#F2EFE9]">Roxas Hatchery (Main Farm)</h2>
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Oriental Mindoro Production & IoT Incubation Hub</span>
                    </div>
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase bg-[#FFC107]/20 text-[#3A3A3A] dark:text-[#FFC107] border border-[#FFC107]/40">
                    Primary Stock
                </span>
            </div>

            <div class="space-y-4">
                @foreach($productsRoxas as $prod)
                    <div class="p-4 rounded-2xl bg-[#FAF9F6] dark:bg-[#1A1917] border border-[#D9CBB8] dark:border-[#3A3833] space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="text-2xl">{{ $prod->size === 'Large' ? '🥚' : ($prod->size === 'Medium' ? '🍳' : '🐣') }}</span>
                                <div>
                                    <h3 class="font-black text-sm text-[#3A3A3A] dark:text-[#F2EFE9]">{{ $prod->size }} Duck Eggs</h3>
                                    <span class="text-[11px] text-gray-500 dark:text-gray-400">{{ $prod->weight_range ?? 'Standard weight' }}</span>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $prod->stock > 20 ? 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300' : 'bg-red-100 dark:bg-red-950/60 text-red-800 dark:text-red-300' }}">
                                {{ $prod->stock }} Dozens Available
                            </span>
                        </div>

                        <!-- Edit Form -->
                        <form onsubmit="updateProductInventory(event, {{ $prod->id }})" class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end pt-2 border-t border-[#D9CBB8]/30 dark:border-[#3A3833]">
                            <div>
                                <label class="text-[10px] uppercase font-bold text-[#7A7A7A] dark:text-[#8E8B83] block mb-1">Price (₱ / doz)</label>
                                <input type="number" step="1" min="0" name="price" value="{{ $prod->price }}" required class="w-full px-3 py-1.5 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] text-xs font-mono font-bold bg-white dark:bg-[#22201D] text-[#3A3A3A] dark:text-[#F2EFE9] focus:border-[#FFC107] outline-none">
                            </div>

                            <div>
                                <label class="text-[10px] uppercase font-bold text-[#7A7A7A] dark:text-[#8E8B83] block mb-1">Available Stock (doz)</label>
                                <input type="number" step="1" min="0" name="stock" value="{{ $prod->stock }}" required class="w-full px-3 py-1.5 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] text-xs font-mono font-bold bg-white dark:bg-[#22201D] text-[#3A3A3A] dark:text-[#F2EFE9] focus:border-[#FFC107] outline-none">
                            </div>

                            <button type="submit" class="egg-btn-primary py-2 px-3 text-xs rounded-xl font-bold w-full">
                                Update
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Branch 2: Boracay Island Depot -->
        <div class="egg-card p-6 bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] rounded-3xl shadow-sm space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-[#D9CBB8]/40 dark:border-[#3A3833]">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-[#EBF3F6] dark:bg-[#1B2529] border border-[#89B4C4] flex items-center justify-center text-xl">
                        🏖️
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-[#3A3A3A] dark:text-[#F2EFE9]">Boracay Island Depot</h2>
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Station 2, Malay, Aklan Express Depot</span>
                    </div>
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase bg-[#89B4C4]/20 text-[#3D6677] dark:text-[#89B4C4] border border-[#89B4C4]/40">
                    Island Stock
                </span>
            </div>

            <div class="space-y-4">
                @foreach($productsBoracay as $prod)
                    <div class="p-4 rounded-2xl bg-[#FAF9F6] dark:bg-[#1A1917] border border-[#D9CBB8] dark:border-[#3A3833] space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="text-2xl">{{ $prod->size === 'Large' ? '🥚' : ($prod->size === 'Medium' ? '🍳' : '🐣') }}</span>
                                <div>
                                    <h3 class="font-black text-sm text-[#3A3A3A] dark:text-[#F2EFE9]">{{ $prod->size }} Duck Eggs</h3>
                                    <span class="text-[11px] text-gray-500 dark:text-gray-400">{{ $prod->weight_range ?? 'Standard weight' }}</span>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $prod->stock > 20 ? 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300' : 'bg-red-100 dark:bg-red-950/60 text-red-800 dark:text-red-300' }}">
                                {{ $prod->stock }} Dozens Available
                            </span>
                        </div>

                        <!-- Edit Form -->
                        <form onsubmit="updateProductInventory(event, {{ $prod->id }})" class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end pt-2 border-t border-[#D9CBB8]/30 dark:border-[#3A3833]">
                            <div>
                                <label class="text-[10px] uppercase font-bold text-[#7A7A7A] dark:text-[#8E8B83] block mb-1">Price (₱ / doz)</label>
                                <input type="number" step="1" min="0" name="price" value="{{ $prod->price }}" required class="w-full px-3 py-1.5 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] text-xs font-mono font-bold bg-white dark:bg-[#22201D] text-[#3A3A3A] dark:text-[#F2EFE9] focus:border-[#89B4C4] outline-none">
                            </div>

                            <div>
                                <label class="text-[10px] uppercase font-bold text-[#7A7A7A] dark:text-[#8E8B83] block mb-1">Available Stock (doz)</label>
                                <input type="number" step="1" min="0" name="stock" value="{{ $prod->stock }}" required class="w-full px-3 py-1.5 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] text-xs font-mono font-bold bg-white dark:bg-[#22201D] text-[#3A3A3A] dark:text-[#F2EFE9] focus:border-[#89B4C4] outline-none">
                            </div>

                            <button type="submit" class="egg-btn-secondary py-2 px-3 text-xs rounded-xl font-bold w-full bg-[#89B4C4] text-white hover:bg-[#6496A9] border-[#89B4C4]">
                                Update
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    function updateProductInventory(e, productId) {
        e.preventDefault();
        const form = e.target;
        const btn = form.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.textContent = 'Saving...';

        const payload = {
            product_id: productId,
            price: parseFloat(form.price.value),
            stock: parseInt(form.stock.value),
        };

        fetch('/admin/inventory/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.textContent = 'Updated!';
            setTimeout(() => btn.textContent = 'Update', 2000);
            if (data.success) {
                window.SmartPalao.cart.showToast(data.message);
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.textContent = 'Update';
            console.error('Inventory error:', err);
            alert('Failed to update inventory.');
        });
    }
</script>
@endpush
