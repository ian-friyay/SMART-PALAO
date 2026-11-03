@extends('layouts.app')

@section('title', 'Smart Palao - Farm-Fresh IoT Incubated Duck Eggs')

@section('content')
<div class="space-y-12 pb-16">

    <!-- Hero Section: Minimalist Egg Vibe -->
    <section class="relative overflow-hidden bg-gradient-to-b from-[#FAF9F6] to-[#F4F1EA] dark:from-[#1C1B19] dark:to-[#161513] py-16 border-b border-[#D9CBB8] dark:border-[#3A3833] transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#FFC107]/20 border border-[#FFC107]/40 text-[#3A3A3A] dark:text-[#F2EFE9] text-xs font-bold">
                        <span class="w-2 h-2 rounded-full bg-[#FFC107] animate-pulse"></span>
                        <span>IoT Incubated • AMG8833 Calibrated • 100% Free-Range Palao Ducks</span>
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-[#3A3A3A] dark:text-[#F2EFE9] tracking-tight leading-[1.1]">
                        Peak Quality Duck Eggs, Guided by <span class="text-[#FFC107] underline decoration-[#D9CBB8] dark:decoration-[#48443E]">Thermal IoT</span>.
                    </h1>

                    <p class="text-base text-[#5A5A5A] dark:text-[#A8A49C] max-w-xl leading-relaxed font-medium">
                        Welcome to Smart Palao. We combine generations of Philippine duck farming heritage in Gloria, Oriental Mindoro with modern 64-sensel thermal telemetry and precision humidity regulation to produce the richest, most consistent duck eggs in the country.
                    </p>

                    <!-- Fulfillment Hub Selection Bar -->
                    <div class="p-4 rounded-2xl bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] shadow-sm max-w-lg transition-colors">
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-[#7A7A7A] dark:text-[#8E8B83] block mb-2">
                            Select Your Nearest Fulfillment Branch:
                        </span>
                        <div class="grid grid-cols-2 gap-3">
                            <form action="{{ route('store.setBranch') }}" method="POST">
                                @csrf
                                <input type="hidden" name="branch" value="Gloria">
                                <button type="submit" class="w-full p-3 rounded-xl border text-left transition flex flex-col justify-between {{ $selectedBranch === 'Gloria' ? 'border-[#FFC107] bg-[#FFF8E1] dark:bg-[#2A261A] shadow-sm ring-2 ring-[#FFC107]/30' : 'border-[#D9CBB8] dark:border-[#3A3833] bg-[#FAF8F5] dark:bg-[#1C1B19] hover:bg-white dark:hover:bg-[#24221F]' }}">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-xs text-[#3A3A3A] dark:text-[#F2EFE9]">Gloria (Main Farm)</span>
                                        @if($selectedBranch === 'Gloria')
                                            <span class="w-2 h-2 rounded-full bg-[#FFC107]"></span>
                                        @endif
                                    </div>
                                    <span class="text-[10px] text-gray-500 dark:text-gray-400 mt-1">Oriental Mindoro Hub</span>
                                </button>
                            </form>

                            <form action="{{ route('store.setBranch') }}" method="POST">
                                @csrf
                                <input type="hidden" name="branch" value="Boracay">
                                <button type="submit" class="w-full p-3 rounded-xl border text-left transition flex flex-col justify-between {{ $selectedBranch === 'Boracay' ? 'border-[#89B4C4] bg-[#EBF3F6] dark:bg-[#1B2529] shadow-sm ring-2 ring-[#89B4C4]/30' : 'border-[#D9CBB8] dark:border-[#3A3833] bg-[#FAF8F5] dark:bg-[#1C1B19] hover:bg-white dark:hover:bg-[#24221F]' }}">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-xs text-[#3A3A3A] dark:text-[#F2EFE9]">Boracay Island Depot</span>
                                        @if($selectedBranch === 'Boracay')
                                            <span class="w-2 h-2 rounded-full bg-[#89B4C4]"></span>
                                        @endif
                                    </div>
                                    <span class="text-[10px] text-gray-500 dark:text-gray-400 mt-1">Malay, Aklan Express</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Visual: Clean Egg Vibe Visual Card with Logo -->
                <div class="lg:col-span-5">
                    <div class="egg-card p-6 bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] rounded-3xl shadow-xl relative overflow-hidden">
                        <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full bg-[#FFC107]/20 blur-2xl pointer-events-none"></div>
                        <div class="absolute -bottom-12 -left-12 w-48 h-48 rounded-full bg-[#89B4C4]/20 blur-2xl pointer-events-none"></div>

                        <div class="flex items-center justify-between pb-4 border-b border-[#D9CBB8]/40 dark:border-[#3A3833]">
                            <div class="flex items-center gap-3">
                                <img src="{{ asset('images/smart-palao-logo.png') }}" alt="Smart Palao" class="w-10 h-10 object-contain rounded-full shadow-sm">
                                <div>
                                    <span class="text-[10px] uppercase font-bold tracking-wider text-[#89B4C4]">Incubation Chamber Alpha</span>
                                    <h3 class="font-extrabold text-sm text-[#3A3A3A] dark:text-[#F2EFE9]">Real-Time Quality Metrics</h3>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300">
                                99.1% Shell Grade
                            </span>
                        </div>

                        <!-- Micro Grid Simulation Display -->
                        <div class="my-5 p-4 rounded-2xl bg-[#FAF9F6] dark:bg-[#1A1917] border border-[#D9CBB8] dark:border-[#3A3833]">
                            <div class="flex items-center justify-between text-xs mb-3">
                                <span class="font-bold text-[#3A3A3A] dark:text-[#F2EFE9]">AMG8833 Thermal Sensel Trace</span>
                                <span class="font-mono text-[11px] text-[#FFC107] font-bold">37.52°C Optimal</span>
                            </div>
                            <div class="grid grid-cols-8 gap-1 aspect-[8/2]">
                                @for($i = 0; $i < 16; $i++)
                                    <div class="rounded bg-[#FFC107]/{{ 60 + ($i % 4) * 10 }} border border-[#D9CBB8]/50 dark:border-[#48443E] h-5 flex items-center justify-center text-[9px] font-mono font-bold text-[#1F1F1F]">
                                        {{ 37.4 + ($i % 3) * 0.1 }}
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <div class="space-y-2 text-xs">
                            <div class="flex items-center justify-between py-1.5 border-b border-gray-100 dark:border-[#2F2D29]">
                                <span class="text-gray-500 dark:text-gray-400">Current Fulfillment Branch:</span>
                                <span class="font-bold text-[#3A3A3A] dark:text-[#F2EFE9]">📍 {{ $selectedBranch }}</span>
                            </div>
                            <div class="flex items-center justify-between py-1.5 border-b border-gray-100 dark:border-[#2F2D29]">
                                <span class="text-gray-500 dark:text-gray-400">Egg Shell Membrane Moisture:</span>
                                <span class="font-bold text-[#89B4C4]">65.2% Equilibrium</span>
                            </div>
                            <div class="flex items-center justify-between py-1.5">
                                <span class="text-gray-500 dark:text-gray-400">Freshness & Fertility Rating:</span>
                                <span class="font-bold text-emerald-600 dark:text-emerald-400">Triple-A Certified</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Catalog Section (3 Standard Items: Large, Medium, Small) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
            <div>
                <span class="text-xs font-extrabold uppercase tracking-widest text-[#FFC107]">Certified Duck Egg Selection</span>
                <h2 class="text-3xl font-black text-[#3A3A3A] dark:text-[#F2EFE9] tracking-tight mt-1">Fresh Farm Catalog</h2>
                <p class="text-xs text-[#7A7A7A] dark:text-[#8E8B83] mt-1">
                    Displaying items fulfilled from <span class="font-bold text-[#3A3A3A] dark:text-[#F2EFE9]">📍 {{ $selectedBranch }} Branch</span>
                </p>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-xs text-[#7A7A7A] dark:text-[#8E8B83]">Change branch:</span>
                <form action="{{ route('store.setBranch') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="branch" value="{{ $selectedBranch === 'Gloria' ? 'Boracay' : 'Gloria' }}">
                    <button type="submit" class="px-3.5 py-1.5 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] bg-white dark:bg-[#22201D] text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] hover:bg-[#FAF8F5] dark:hover:bg-[#2D2B27] transition shadow-sm">
                        Switch to {{ $selectedBranch === 'Gloria' ? 'Boracay' : 'Gloria' }}
                    </button>
                </form>
            </div>
        </div>

        <!-- 3 Product Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($products as $product)
                <div class="egg-card bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] rounded-3xl p-6 shadow-sm hover:shadow-md transition flex flex-col justify-between relative group">
                    <!-- Top Egg Badge -->
                    <div class="flex items-start justify-between">
                        <div class="w-16 h-16 rounded-2xl bg-[#FFF8E1] dark:bg-[#2D2817] border border-[#FFC107]/40 flex items-center justify-center text-3xl shadow-sm">
                            @if($product->size === 'Large')
                                🥚
                            @elseif($product->size === 'Medium')
                                🍳
                            @else
                                🐣
                            @endif
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-extrabold uppercase {{ $product->stock > 0 ? 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300' : 'bg-red-100 dark:bg-red-950/60 text-red-800 dark:text-red-300' }}">
                            {{ $product->stock > 0 ? 'In Stock (' . $product->stock . ' doz)' : 'Out of Stock' }}
                        </span>
                    </div>

                    <!-- Details -->
                    <div class="my-5 space-y-2">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-black text-[#3A3A3A] dark:text-[#F2EFE9]">{{ $product->size }} Duck Eggs</h3>
                            <span class="text-xs font-bold font-mono text-[#89B4C4] bg-[#89B4C4]/10 dark:bg-[#89B4C4]/20 px-2 py-0.5 rounded">
                                {{ $product->weight_range ?? 'Standard' }}
                            </span>
                        </div>
                        <p class="text-xs text-[#5A5A5A] dark:text-[#A8A49C] leading-relaxed line-clamp-3">
                            {{ $product->description }}
                        </p>
                    </div>

                    <!-- Price & Add To Cart Button -->
                    <div class="pt-4 border-t border-[#D9CBB8]/40 dark:border-[#3A3833] flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-[#7A7A7A] dark:text-[#8E8B83] uppercase font-bold block">Price per Dozen</span>
                            <div class="flex items-baseline gap-0.5">
                                <span class="text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9]">₱</span>
                                <span class="text-2xl font-black font-mono text-[#3A3A3A] dark:text-[#F2EFE9]">{{ number_format($product->price, 2) }}</span>
                            </div>
                        </div>

                        <button 
                            onclick="addToCart({{ json_encode($product) }})"
                            {{ $product->stock <= 0 ? 'disabled' : '' }}
                            class="egg-btn-primary px-4 py-2.5 rounded-xl text-xs font-bold {{ $product->stock <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                        >
                            <span>+ Add to Cart</span>
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-gray-500 bg-white dark:bg-[#22201D] rounded-3xl border border-[#D9CBB8] dark:border-[#3A3833]">
                    No egg products found for this branch.
                </div>
            @endforelse
        </div>
    </section>

    <!-- Why Smart Palao: IoT Egg Incubation Story -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-16">
        <div class="egg-card p-8 md:p-12 bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] rounded-3xl shadow-sm">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <span class="text-xs font-bold uppercase tracking-widest text-[#FFC107]">The Smart Palao Difference</span>
                <h2 class="text-3xl font-black text-[#3A3A3A] dark:text-[#F2EFE9] tracking-tight mt-1">Why IoT Incubation Changes Everything</h2>
                <p class="text-xs text-[#7A7A7A] dark:text-[#8E8B83] mt-2">
                    Traditional egg incubation suffers from localized thermal dead zones and uncontrolled humidity swings. Smart Palao eliminates uncertainty.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Pillar 1 -->
                <div class="p-5 rounded-2xl bg-[#FAF9F6] dark:bg-[#1C1B19] border border-[#D9CBB8] dark:border-[#3A3833] space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-[#89B4C4]/20 text-[#3D6677] dark:text-[#89B4C4] flex items-center justify-center font-black text-lg">
                        📡
                    </div>
                    <h3 class="font-extrabold text-sm text-[#3A3A3A] dark:text-[#F2EFE9]">AMG8833 Thermal Imaging</h3>
                    <p class="text-xs text-[#5A5A5A] dark:text-[#A8A49C] leading-relaxed">
                        Rather than single-point probes, an 8x8 infrared sensor array maps surface heat dissipation across 64 tray sectors, preventing lethal hot spots.
                    </p>
                </div>

                <!-- Pillar 2 -->
                <div class="p-5 rounded-2xl bg-[#FAF9F6] dark:bg-[#1C1B19] border border-[#D9CBB8] dark:border-[#3A3833] space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-[#FFC107]/20 text-[#1F1F1F] dark:text-[#FFC107] flex items-center justify-center font-black text-lg">
                        ⚙️
                    </div>
                    <h3 class="font-extrabold text-sm text-[#3A3A3A] dark:text-[#F2EFE9]">Closed-Loop PID Control</h3>
                    <p class="text-xs text-[#5A5A5A] dark:text-[#A8A49C] leading-relaxed">
                        High-frequency PWM intake and exhaust airflow adjusts within ±0.1°C of the 37.5°C setpoint, maintaining exact eggshell membrane permeability.
                    </p>
                </div>

                <!-- Pillar 3 -->
                <div class="p-5 rounded-2xl bg-[#FAF9F6] dark:bg-[#1C1B19] border border-[#D9CBB8] dark:border-[#3A3833] space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-[#D9CBB8]/40 dark:bg-[#3A3833] text-[#3A3A3A] dark:text-[#F2EFE9] flex items-center justify-center font-black text-lg">
                        🔬
                    </div>
                    <h3 class="font-extrabold text-sm text-[#3A3A3A] dark:text-[#F2EFE9]">Digital Candling Assurance</h3>
                    <p class="text-xs text-[#5A5A5A] dark:text-[#A8A49C] leading-relaxed">
                        Every single egg is indexed with digital vitality telemetry, ensuring only 100% prime-grade fertile or culinary eggs reach your table.
                    </p>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection

@push('scripts')
<script>
    function addToCart(product) {
        window.SmartPalao.cart.addItem(product, 1);
    }
</script>
@endpush
