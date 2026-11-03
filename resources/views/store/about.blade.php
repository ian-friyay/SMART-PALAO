@extends('layouts.app')

@section('title', 'About Us - Smart Palao IoT Duck Egg Farm')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-16">

    <!-- Header Section -->
    <div class="text-center max-w-3xl mx-auto space-y-4">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#FFC107]/20 border border-[#FFC107]/40 text-[#3A3A3A] dark:text-[#F2EFE9] text-xs font-bold">
            <span>🌾 Generations of Heritage • Powered by IoT Innovation</span>
        </div>
        <h1 class="text-4xl sm:text-5xl font-black text-[#3A3A3A] dark:text-[#F2EFE9] tracking-tight">
            The Story Behind <span class="text-[#FFC107]">Smart Palao</span>
        </h1>
        <p class="text-sm text-[#5A5A5A] dark:text-[#A8A49C] leading-relaxed">
            From the fertile wetland pastures of Gloria, Oriental Mindoro to the premier island resorts of Boracay, discover how smart incubation telematics elevates traditional Philippine duck farming.
        </p>
    </div>

    <!-- Farm Heritage & Origins Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="space-y-6">
            <span class="text-xs font-extrabold uppercase tracking-widest text-[#FFC107]">Our Heritage</span>
            <h2 class="text-3xl font-black text-[#3A3A3A] dark:text-[#F2EFE9] tracking-tight">
                Roots in Gloria, Oriental Mindoro
            </h2>
            <p class="text-sm text-[#5A5A5A] dark:text-[#A8A49C] leading-relaxed">
                For over three decades, the Palao family has raised authentic <span class="font-bold text-[#3A3A3A] dark:text-[#F2EFE9]">Itik Pinas (Pateros Mallard)</span> ducks along the freshwater river systems of Gloria in Oriental Mindoro. Our ducks roam free in expansive open pastures, foraging on nutrient-dense freshwater snails, local grains, and river flora.
            </p>
            <p class="text-sm text-[#5A5A5A] dark:text-[#A8A49C] leading-relaxed">
                This natural lifestyle creates duck eggs renowned for extraordinarily vibrant orange yolks, thick protein-rich albumens, and deep culinary flavor. However, traditional charcoal and kerosene incubator huts struggled with unpredictable tropical weather swings—causing cold spots, humidity spikes, and high embryonic mortality.
            </p>
            <div class="p-4 rounded-2xl bg-[#FFF8E1] dark:bg-[#2A261A] border border-[#FFC107] text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] flex items-center gap-3">
                <span class="text-2xl">💡</span>
                <span>The Mission: Modernize traditional duck husbandry with precision open-source microelectronics while keeping all duck welfare 100% free-range.</span>
            </div>
        </div>

        <div class="egg-card p-6 bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] rounded-3xl shadow-lg space-y-4">
            <div class="w-full h-64 rounded-2xl bg-gradient-to-br from-[#FAF8F5] via-[#EBE5D8] to-[#D9CBB8] dark:from-[#1C1B19] dark:via-[#262420] dark:to-[#332F2A] flex flex-col items-center justify-center text-center p-6 border border-[#D9CBB8] dark:border-[#3A3833]">
                <img src="{{ asset('images/smart-palao-logo.png') }}" alt="Smart Palao Emblem" class="w-28 h-28 object-contain rounded-full shadow-md mb-2">
                <span class="font-black text-lg text-[#3A3A3A] dark:text-[#F2EFE9]">Palao Pastureland & Hatchery Hub</span>
                <span class="text-xs text-[#7A7A7A] dark:text-[#A8A49C] mt-0.5">Gloria, Oriental Mindoro (Main) • Boracay Express Depot</span>
            </div>
            <div class="grid grid-cols-3 gap-2 text-center text-xs">
                <div class="p-2.5 rounded-xl bg-[#FAF9F6] dark:bg-[#1A1917] border border-[#D9CBB8] dark:border-[#3A3833]">
                    <span class="font-mono font-black text-sm text-[#3A3A3A] dark:text-[#F2EFE9]">30+</span>
                    <span class="block text-[10px] text-gray-500 dark:text-gray-400">Years Farming</span>
                </div>
                <div class="p-2.5 rounded-xl bg-[#FAF9F6] dark:bg-[#1A1917] border border-[#D9CBB8] dark:border-[#3A3833]">
                    <span class="font-mono font-black text-sm text-[#FFC107]">99.2%</span>
                    <span class="block text-[10px] text-gray-500 dark:text-gray-400">Egg Viability</span>
                </div>
                <div class="p-2.5 rounded-xl bg-[#FAF9F6] dark:bg-[#1A1917] border border-[#D9CBB8] dark:border-[#3A3833]">
                    <span class="font-mono font-black text-sm text-[#89B4C4]">64 Sensels</span>
                    <span class="block text-[10px] text-gray-500 dark:text-gray-400">Thermal Telemetry</span>
                </div>
            </div>
        </div>
    </div>

    <!-- The Technical IoT Edge -->
    <div class="egg-card p-8 md:p-12 bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] rounded-3xl shadow-sm space-y-10">
        <div class="text-center max-w-2xl mx-auto space-y-2">
            <span class="text-xs font-bold uppercase tracking-widest text-[#89B4C4]">Engineering Excellence</span>
            <h2 class="text-3xl font-black text-[#3A3A3A] dark:text-[#F2EFE9] tracking-tight">How Smart Palao IoT Ensures Superior Eggs</h2>
            <p class="text-xs text-[#7A7A7A] dark:text-[#8E8B83]">
                Every single egg batch in our hatchery is governed by automated telematics hardware.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-6 rounded-2xl bg-[#FAF9F6] dark:bg-[#1C1B19] border border-[#D9CBB8] dark:border-[#3A3833] space-y-3">
                <div class="w-12 h-12 rounded-xl bg-[#89B4C4] text-white flex items-center justify-center font-bold text-xl shadow-sm">
                    1
                </div>
                <h3 class="font-bold text-base text-[#3A3A3A] dark:text-[#F2EFE9]">AMG8833 Thermal Heatmap</h3>
                <p class="text-xs text-[#5A5A5A] dark:text-[#A8A49C] leading-relaxed">
                    A Panasonic AMG8833 infrared thermopile camera continuously sweeps the incubator tray across an 8x8 matrix (64 temperature points). Any temperature variance exceeding ±0.2°C triggers automatic zone circulation, eliminating dead cold spots.
                </p>
            </div>

            <div class="p-6 rounded-2xl bg-[#FAF9F6] dark:bg-[#1C1B19] border border-[#D9CBB8] dark:border-[#3A3833] space-y-3">
                <div class="w-12 h-12 rounded-xl bg-[#FFC107] text-[#1F1F1F] flex items-center justify-center font-bold text-xl shadow-sm">
                    2
                </div>
                <h3 class="font-bold text-base text-[#3A3A3A] dark:text-[#F2EFE9]">SHT31 Microclimate PID</h3>
                <p class="text-xs text-[#5A5A5A] dark:text-[#A8A49C] leading-relaxed">
                    Sensirion SHT31 dual humidity and temperature sensors govern solid-state heating elements and variable PWM exhaust fans. Duck eggs require high 65% RH for 25 days, followed by 75% RH for hatching—maintained with digital precision.
                </p>
            </div>

            <div class="p-6 rounded-2xl bg-[#FAF9F6] dark:bg-[#1C1B19] border border-[#D9CBB8] dark:border-[#3A3833] space-y-3">
                <div class="w-12 h-12 rounded-xl bg-[#D9CBB8] dark:bg-[#3A3833] text-[#3A3A3A] dark:text-[#F2EFE9] flex items-center justify-center font-bold text-xl shadow-sm">
                    3
                </div>
                <h3 class="font-bold text-base text-[#3A3A3A] dark:text-[#F2EFE9]">Digital Candling Classification</h3>
                <p class="text-xs text-[#5A5A5A] dark:text-[#A8A49C] leading-relaxed">
                    At Day 14, every egg undergoes high-intensity digital candling. Results (viable embryo vs. non-viable) are logged into the system, ensuring that culinary table eggs and breeding eggs are sorted with zero guesswork.
                </p>
            </div>
        </div>
    </div>

    <!-- Dual Branch Operations -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="p-6 rounded-3xl bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] shadow-sm space-y-3">
            <div class="flex items-center gap-2">
                <span class="text-2xl">🌾</span>
                <h3 class="font-black text-lg text-[#3A3A3A] dark:text-[#F2EFE9]">Gloria Hatchery (Main Farm)</h3>
            </div>
            <p class="text-xs text-[#5A5A5A] dark:text-[#A8A49C] leading-relaxed">
                Located in Gloria, Oriental Mindoro. Houses our primary free-range duck pastures, breeding flocks, egg selection grading facility, and IoT incubator chambers Alpha & Beta. Provides bulk egg trays for mainland Mindoro and Luzon distribution.
            </p>
            <div class="text-xs text-emerald-600 dark:text-emerald-400 font-bold">
                ✓ Same-day farm harvest & packing
            </div>
        </div>

        <div class="p-6 rounded-3xl bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] shadow-sm space-y-3">
            <div class="flex items-center gap-2">
                <span class="text-2xl">🏖️</span>
                <h3 class="font-black text-lg text-[#3A3A3A] dark:text-[#F2EFE9]">Boracay Island Depot</h3>
            </div>
            <p class="text-xs text-[#5A5A5A] dark:text-[#A8A49C] leading-relaxed">
                Located at Station 2, Boracay Island, Malay, Aklan. Receives daily temperature-controlled ferry dispatches directly from Gloria. Supplies Boracay's top breakfast cafes, five-star resort buffets, and local residents with fresh duck eggs.
            </p>
            <div class="text-xs text-[#89B4C4] font-bold">
                ✓ Express e-trike island drop-off service
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="text-center bg-[#FAF9F6] dark:bg-[#1C1B19] p-8 rounded-3xl border border-[#D9CBB8] dark:border-[#3A3833] space-y-4">
        <h2 class="text-2xl font-black text-[#3A3A3A] dark:text-[#F2EFE9]">Experience the Smart Palao Quality</h2>
        <p class="text-xs text-[#7A7A7A] dark:text-[#8E8B83] max-w-md mx-auto">
            Order fresh Large, Medium, or Small duck eggs delivered right to your home or commercial kitchen.
        </p>
        <a href="{{ route('store') }}" class="egg-btn-primary px-6 py-3 font-bold text-sm rounded-xl">
            Browse Product Catalog & Order Now
        </a>
    </div>

</div>
@endsection
