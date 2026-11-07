<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Smart Palao - IoT Duck Egg Incubation & Store')</title>

    <!-- Favicon using the uploaded logo -->
    <link rel="icon" type="image/png" href="{{ asset('images/smart-palao-logo.png') }}">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Immediate Dark Mode Init (prevents screen flicker) -->
    <script>
        if (localStorage.getItem('smart_palao_theme') === 'dark' || (!('smart_palao_theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col bg-[#F4F1EA] dark:bg-[#161513] text-[#3A3A3A] dark:text-[#F2EFE9] antialiased transition-colors duration-200">

    <!-- Unified Minimalist Navigation Bar -->
    <header class="sticky top-0 z-50 bg-[#FAF9F6]/95 dark:bg-[#1C1B19]/95 backdrop-blur-md border-b border-[#D9CBB8] dark:border-[#3A3833] transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20 gap-3">
                
                <!-- Brand: Logo & Clean Title (No extra descriptions) -->
                <div class="flex items-center gap-4 lg:gap-6 shrink-0">
                    <a href="{{ route('store') }}" class="flex items-center gap-2.5 sm:gap-3.5 group shrink-0">
                        <div class="relative w-9 h-9 sm:w-11 sm:h-11 rounded-full overflow-hidden shadow-sm ring-2 ring-[#FFC107]/60 group-hover:ring-[#FFC107] group-hover:scale-105 transition-all shrink-0">
                            <img src="{{ asset('images/smart-palao-logo.png') }}" alt="Smart Palao Logo" class="w-full h-full object-cover">
                        </div>
                        <span class="font-black text-base sm:text-xl tracking-tight text-[#3A3A3A] dark:text-[#F2EFE9] select-none whitespace-nowrap">
                            SMART PALAO
                        </span>
                    </a>

                    <!-- Main Navigation Links (Desktop) -->
                    <nav class="hidden lg:flex items-center gap-1.5 ml-2">
                        <a href="{{ route('store') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('store') ? 'bg-[#FFC107] text-[#1F1F1F] shadow-sm' : 'text-[#5A5A5A] dark:text-[#A8A49C] hover:bg-[#EBE5D8] dark:hover:bg-[#2A2824]' }}">
                            Storefront
                        </a>
                        <a href="{{ route('store.about') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('store.about') ? 'bg-[#FFC107] text-[#1F1F1F] shadow-sm' : 'text-[#5A5A5A] dark:text-[#A8A49C] hover:bg-[#EBE5D8] dark:hover:bg-[#2A2824]' }}">
                            About
                        </a>

                        @auth
                            @if(auth()->user()->isOwner())
                                <div class="h-4 w-px bg-[#D9CBB8] dark:bg-[#3A3833] mx-1"></div>
                                <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('admin.dashboard') ? 'bg-[#FFC107] text-[#1F1F1F] shadow-sm' : 'text-[#3A3A3A] dark:text-[#F2EFE9] hover:bg-[#EBE5D8] dark:hover:bg-[#2A2824]' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span>IoT Dashboard</span>
                                </a>
                                <a href="{{ route('admin.inventory') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.inventory') ? 'bg-[#FFC107] text-[#1F1F1F] shadow-sm' : 'text-[#5A5A5A] dark:text-[#A8A49C] hover:bg-[#EBE5D8] dark:hover:bg-[#2A2824]' }}">
                                    Inventory
                                </a>
                                <a href="{{ route('admin.orders') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.orders') ? 'bg-[#FFC107] text-[#1F1F1F] shadow-sm' : 'text-[#5A5A5A] dark:text-[#A8A49C] hover:bg-[#EBE5D8] dark:hover:bg-[#2A2824]' }}">
                                    Kanban
                                </a>
                            @else
                                <a href="{{ route('store.orders') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('store.orders') ? 'bg-[#FFC107] text-[#1F1F1F] shadow-sm' : 'text-[#5A5A5A] dark:text-[#A8A49C] hover:bg-[#EBE5D8] dark:hover:bg-[#2A2824]' }}">
                                    My Orders
                                </a>
                            @endif
                        @endauth
                    </nav>
                </div>

                <!-- Right Utility Bar -->
                <div class="flex items-center gap-2 sm:gap-3">
                    
                    <!-- Desktop: Branch Selector Pill -->
                    <form action="{{ route('store.setBranch') }}" method="POST" class="relative hidden md:block">
                        @csrf
                        <div class="flex items-center rounded-xl bg-white dark:bg-[#24221F] border border-[#D9CBB8] dark:border-[#3A3833] px-2.5 py-1.5 shadow-sm text-xs font-bold">
                            <span class="text-[#89B4C4] mr-1">📍</span>
                            <select name="branch" onchange="this.form.submit()" class="bg-transparent font-bold text-[#3A3A3A] dark:text-[#F2EFE9] focus:outline-none cursor-pointer text-xs">
                                <option value="Roxas" {{ session('selected_branch', 'Roxas') === 'Roxas' ? 'selected' : '' }}>Roxas (Main)</option>
                                <option value="Boracay" {{ session('selected_branch', 'Roxas') === 'Boracay' ? 'selected' : '' }}>Boracay</option>
                            </select>
                        </div>
                    </form>

                    <!-- Dark Mode Toggle Button (Always accessible) -->
                    <button 
                        id="theme-toggle-btn"
                        onclick="toggleDarkMode()" 
                        type="button" 
                        class="p-2 rounded-xl bg-white dark:bg-[#24221F] border border-[#D9CBB8] dark:border-[#3A3833] hover:border-[#FFC107] text-[#3A3A3A] dark:text-[#FFC107] transition shadow-sm flex items-center justify-center w-9 h-9 shrink-0" 
                        title="Toggle Light / Dark Mode"
                    >
                        <span id="theme-icon-light" class="hidden dark:inline text-base">☀️</span>
                        <span id="theme-icon-dark" class="inline dark:hidden text-base">🌙</span>
                    </button>

                    <!-- Shopping Cart Button (Always accessible) -->
                    <a href="{{ route('store.cart') }}" class="relative p-2 rounded-xl bg-white dark:bg-[#24221F] border border-[#D9CBB8] dark:border-[#3A3833] hover:border-[#FFC107] text-[#3A3A3A] dark:text-[#F2EFE9] transition shadow-sm flex items-center justify-center w-9 h-9 shrink-0">
                        <svg class="w-4 h-4 text-current" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span class="cart-badge absolute -top-1.5 -right-1.5 hidden bg-[#FFC107] text-[#1F1F1F] text-[10px] font-black w-4 h-4 rounded-full items-center justify-center border-2 border-white dark:border-[#24221F] shadow">
                            0
                        </span>
                    </a>

                    <!-- Desktop: Clean 1-Click Role Switcher Pill -->
                    <div class="relative group hidden md:block">
                        <button class="flex items-center gap-1.5 py-1.5 px-2.5 rounded-xl bg-white dark:bg-[#24221F] border border-[#D9CBB8] dark:border-[#3A3833] text-xs font-bold shadow-sm hover:border-[#FFC107] transition">
                            <span class="w-2 h-2 rounded-full {{ auth()->check() && auth()->user()->isOwner() ? 'bg-[#FFC107]' : 'bg-[#89B4C4]' }}"></span>
                            <span class="text-[11px] uppercase tracking-wide text-[#3A3A3A] dark:text-[#F2EFE9]">
                                {{ auth()->check() ? auth()->user()->role : 'Guest' }}
                            </span>
                            <svg class="w-3 h-3 text-gray-400 group-hover:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <!-- Role Switch Dropdown -->
                        <div class="absolute right-0 mt-2 w-52 bg-white dark:bg-[#24221F] rounded-2xl shadow-xl border border-[#D9CBB8] dark:border-[#3A3833] p-2 hidden group-hover:block transition-all z-50">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block px-2.5 py-1">Quick Role Switch</span>
                            <a href="{{ route('auth.quickLogin', 'owner') }}" class="flex items-center gap-2 px-2.5 py-2 rounded-xl text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] hover:bg-[#FFF8E1] dark:hover:bg-[#2F2C26] hover:text-[#B38600] transition">
                                <span>👑</span>
                                <span>Switch to Owner (Admin)</span>
                            </a>
                            <a href="{{ route('auth.quickLogin', 'buyer') }}" class="flex items-center gap-2 px-2.5 py-2 rounded-xl text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] hover:bg-[#EBF3F6] dark:hover:bg-[#242B2E] hover:text-[#4A7C8E] transition">
                                <span>🛒</span>
                                <span>Switch to Buyer (Customer)</span>
                            </a>
                        </div>
                    </div>

                    <!-- Desktop: User Account / Logout Menu -->
                    <div class="hidden md:block">
                        @auth
                            <div class="relative group">
                                <button class="flex items-center gap-2 p-1 rounded-xl hover:bg-[#EBE5D8] dark:hover:bg-[#2A2824] transition">
                                    <div class="w-8 h-8 rounded-full {{ auth()->user()->isOwner() ? 'bg-[#FFC107] text-[#1F1F1F]' : 'bg-[#89B4C4] text-white' }} flex items-center justify-center font-black text-xs shadow-sm">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                </button>
                                <div class="absolute right-0 mt-2 w-48 bg-white dark:bg-[#24221F] rounded-2xl shadow-xl border border-[#D9CBB8] dark:border-[#3A3833] py-2 hidden group-hover:block transition-all z-50">
                                    <div class="px-4 py-2 border-b border-[#D9CBB8]/50 dark:border-[#3A3833]">
                                        <p class="text-[11px] font-extrabold text-[#3A3A3A] dark:text-[#F2EFE9] truncate">{{ auth()->user()->name }}</p>
                                        <p class="text-[10px] text-gray-400 truncate">{{ auth()->user()->email }}</p>
                                    </div>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-xs font-bold text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 transition">
                                            Log Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('login') }}" class="px-3 py-1.5 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] bg-white dark:bg-[#24221F] text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] hover:bg-[#FAF8F5] dark:hover:bg-[#2D2B27] transition shadow-sm">
                                    Log In
                                </a>
                                <a href="{{ route('register') }}" class="px-3 py-1.5 rounded-xl bg-[#FFC107] text-xs font-bold text-[#1F1F1F] hover:bg-[#E5AC00] transition shadow-sm">
                                    Register
                                </a>
                            </div>
                        @endauth
                    </div>

                    <!-- Mobile & Tablet Hamburger Button -->
                    <button 
                        id="mobile-drawer-btn" 
                        onclick="toggleMobileDrawer()" 
                        type="button" 
                        aria-label="Toggle Navigation Menu" 
                        class="flex lg:hidden p-2 rounded-xl bg-white dark:bg-[#24221F] border border-[#D9CBB8] dark:border-[#3A3833] hover:border-[#FFC107] text-[#3A3A3A] dark:text-[#F2EFE9] transition shadow-sm w-9 h-9 items-center justify-center shrink-0"
                    >
                        <svg id="hamburger-bars-icon" class="w-5 h-5 block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg id="hamburger-close-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                </div>
            </div>
        </div>

        <!-- Mobile & Tablet Slide-Down Drawer Sheet -->
        <div id="mobile-drawer" class="hidden lg:hidden border-t border-[#D9CBB8] dark:border-[#3A3833] bg-[#FAF9F6]/98 dark:bg-[#1C1B19]/98 backdrop-blur-xl shadow-2xl transition-all">
            <div class="px-4 py-5 space-y-4 max-h-[calc(100vh-4.5rem)] overflow-y-auto">
                
                <!-- 1. Fulfillment Branch Selector -->
                <div class="p-3.5 rounded-2xl bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] shadow-sm">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#7A7A7A] dark:text-[#8E8B83] block mb-2">
                        📍 Fulfillment Branch Location
                    </span>
                    <div class="grid grid-cols-2 gap-2">
                        <form action="{{ route('store.setBranch') }}" method="POST">
                            @csrf
                            <input type="hidden" name="branch" value="Roxas">
                            <button type="submit" class="w-full py-2.5 px-3 rounded-xl text-xs font-bold text-center border transition flex items-center justify-center gap-1.5 {{ session('selected_branch', 'Roxas') === 'Roxas' ? 'bg-[#FFF8E1] dark:bg-[#2A261A] text-[#1F1F1F] dark:text-[#FFC107] border-[#FFC107] ring-1 ring-[#FFC107]' : 'bg-[#FAF8F5] dark:bg-[#1A1917] text-[#5A5A5A] dark:text-[#A8A49C] border-[#D9CBB8] dark:border-[#3A3833]' }}">
                                <span class="w-2 h-2 rounded-full {{ session('selected_branch', 'Roxas') === 'Roxas' ? 'bg-[#FFC107]' : 'bg-transparent' }}"></span>
                                <span>🌾 Roxas (Main)</span>
                            </button>
                        </form>
                        <form action="{{ route('store.setBranch') }}" method="POST">
                            @csrf
                            <input type="hidden" name="branch" value="Boracay">
                            <button type="submit" class="w-full py-2.5 px-3 rounded-xl text-xs font-bold text-center border transition flex items-center justify-center gap-1.5 {{ session('selected_branch', 'Roxas') === 'Boracay' ? 'bg-[#EBF3F6] dark:bg-[#1B2529] text-[#1F1F1F] dark:text-[#89B4C4] border-[#89B4C4] ring-1 ring-[#89B4C4]' : 'bg-[#FAF8F5] dark:bg-[#1A1917] text-[#5A5A5A] dark:text-[#A8A49C] border-[#D9CBB8] dark:border-[#3A3833]' }}">
                                <span class="w-2 h-2 rounded-full {{ session('selected_branch', 'Roxas') === 'Boracay' ? 'bg-[#89B4C4]' : 'bg-transparent' }}"></span>
                                <span>🏖️ Boracay</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- 2. Role Switcher -->
                <div class="p-3.5 rounded-2xl bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#7A7A7A] dark:text-[#8E8B83]">
                            👑 Quick Role Switch
                        </span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ auth()->check() && auth()->user()->isOwner() ? 'bg-[#FFC107]/20 text-[#B38600] dark:text-[#FFC107]' : 'bg-[#89B4C4]/20 text-[#3D6677] dark:text-[#89B4C4]' }}">
                            Current: {{ auth()->check() ? ucfirst(auth()->user()->role) : 'Guest' }}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('auth.quickLogin', 'owner') }}" class="py-2.5 px-3 rounded-xl text-xs font-bold text-center flex items-center justify-center gap-1.5 border transition {{ auth()->check() && auth()->user()->isOwner() ? 'bg-[#FFF8E1] dark:bg-[#2A261A] text-[#1F1F1F] dark:text-[#FFC107] border-[#FFC107] ring-1 ring-[#FFC107]' : 'bg-[#FAF8F5] dark:bg-[#1A1917] text-[#5A5A5A] dark:text-[#A8A49C] border-[#D9CBB8] dark:border-[#3A3833]' }}">
                            <span>👑 Owner (Admin)</span>
                        </a>
                        <a href="{{ route('auth.quickLogin', 'buyer') }}" class="py-2.5 px-3 rounded-xl text-xs font-bold text-center flex items-center justify-center gap-1.5 border transition {{ auth()->check() && !auth()->user()->isOwner() ? 'bg-[#EBF3F6] dark:bg-[#1B2529] text-[#1F1F1F] dark:text-[#89B4C4] border-[#89B4C4] ring-1 ring-[#89B4C4]' : 'bg-[#FAF8F5] dark:bg-[#1A1917] text-[#5A5A5A] dark:text-[#A8A49C] border-[#D9CBB8] dark:border-[#3A3833]' }}">
                            <span>🛒 Buyer (Store)</span>
                        </a>
                    </div>
                </div>

                <!-- 3. Navigation Links List -->
                <div class="space-y-1">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#7A7A7A] dark:text-[#8E8B83] block px-2 mb-1">
                        Site Menu
                    </span>
                    <a href="{{ route('store') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-xs font-bold transition {{ request()->routeIs('store') ? 'bg-[#FFC107] text-[#1F1F1F] shadow-sm' : 'text-[#3A3A3A] dark:text-[#F2EFE9] hover:bg-[#EBE5D8] dark:hover:bg-[#2A2824]' }}">
                        <span class="text-base">🏪</span>
                        <span>Storefront Catalog</span>
                    </a>
                    <a href="{{ route('store.about') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-xs font-bold transition {{ request()->routeIs('store.about') ? 'bg-[#FFC107] text-[#1F1F1F] shadow-sm' : 'text-[#3A3A3A] dark:text-[#F2EFE9] hover:bg-[#EBE5D8] dark:hover:bg-[#2A2824]' }}">
                        <span class="text-base">🌾</span>
                        <span>About Farm & IoT Heritage</span>
                    </a>

                    @auth
                        @if(auth()->user()->isOwner())
                            <div class="pt-3 pb-1 px-2 text-[10px] font-extrabold uppercase tracking-wider text-[#FFC107]">
                                Owner Controls & Telematics
                            </div>
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between px-3.5 py-3 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#FFC107] text-[#1F1F1F] shadow-sm' : 'text-[#3A3A3A] dark:text-[#F2EFE9] hover:bg-[#EBE5D8] dark:hover:bg-[#2A2824]' }}">
                                <div class="flex items-center gap-3">
                                    <span class="text-base">📡</span>
                                    <span>IoT Incubation Dashboard</span>
                                </div>
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            </a>
                            <a href="{{ route('admin.inventory') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.inventory') ? 'bg-[#FFC107] text-[#1F1F1F] shadow-sm' : 'text-[#3A3A3A] dark:text-[#F2EFE9] hover:bg-[#EBE5D8] dark:hover:bg-[#2A2824]' }}">
                                <span class="text-base">📦</span>
                                <span>Multi-Branch Inventory</span>
                            </a>
                            <a href="{{ route('admin.orders') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.orders') ? 'bg-[#FFC107] text-[#1F1F1F] shadow-sm' : 'text-[#3A3A3A] dark:text-[#F2EFE9] hover:bg-[#EBE5D8] dark:hover:bg-[#2A2824]' }}">
                                <span class="text-base">📋</span>
                                <span>Order Fulfillment Kanban</span>
                            </a>
                        @else
                            <a href="{{ route('store.orders') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-xs font-bold transition {{ request()->routeIs('store.orders') ? 'bg-[#FFC107] text-[#1F1F1F] shadow-sm' : 'text-[#3A3A3A] dark:text-[#F2EFE9] hover:bg-[#EBE5D8] dark:hover:bg-[#2A2824]' }}">
                                <span class="text-base">🛍️</span>
                                <span>My Order History</span>
                            </a>
                        @endif
                    @endauth
                </div>

                <!-- 4. User Session & Authentication -->
                <div class="pt-3 border-t border-[#D9CBB8]/50 dark:border-[#3A3833]">
                    @auth
                        <div class="p-3.5 rounded-2xl bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full {{ auth()->user()->isOwner() ? 'bg-[#FFC107] text-[#1F1F1F]' : 'bg-[#89B4C4] text-white' }} flex items-center justify-center font-black text-xs shadow-sm shrink-0">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <div class="truncate max-w-[150px]">
                                    <p class="text-xs font-black text-[#3A3A3A] dark:text-[#F2EFE9] truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-[10px] text-gray-400 truncate">{{ auth()->user()->email }}</p>
                                </div>
                            </div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-bold text-red-600 bg-red-50 dark:bg-red-950/40 hover:bg-red-100 transition">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('login') }}" class="py-2.5 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] bg-white dark:bg-[#24221F] text-xs font-bold text-center text-[#3A3A3A] dark:text-[#F2EFE9] shadow-sm">
                                Log In
                            </a>
                            <a href="{{ route('register') }}" class="py-2.5 rounded-xl bg-[#FFC107] text-xs font-bold text-center text-[#1F1F1F] shadow-sm">
                                Register
                            </a>
                        </div>
                    @endauth
                </div>

            </div>
        </div>
    </header>

    <!-- Global Flash Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-[#FFF8E1] dark:bg-[#292518] border-2 border-[#FFC107] text-[#3A3A3A] dark:text-[#F2EFE9] text-xs font-bold flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <span class="text-lg">✨</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 font-bold">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-2xl bg-red-50 dark:bg-red-950/40 border-2 border-red-300 dark:border-red-800 text-red-800 dark:text-red-200 text-xs font-bold flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <span class="text-lg">⚠️</span>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-700 dark:hover:text-red-200 font-bold">&times;</button>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#FAF9F6] dark:bg-[#1C1B19] border-t border-[#D9CBB8] dark:border-[#3A3833] mt-16 py-12 text-[#5A5A5A] dark:text-[#A8A49C] transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Col 1: Bio with Logo -->
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/smart-palao-logo.png') }}" alt="Smart Palao Logo" class="w-9 h-9 object-contain rounded-full shadow-sm">
                        <span class="font-black text-lg text-[#3A3A3A] dark:text-[#F2EFE9] tracking-tight">SMART PALAO</span>
                    </div>
                    <p class="text-xs text-[#7A7A7A] dark:text-[#8E8B83] leading-relaxed">
                        Next-generation duck egg incubation powered by real-time camera telemetry and SHT31 microclimate telematics. Serving Roxas, Oriental Mindoro and Boracay Island.
                    </p>
                </div>

                <!-- Col 2: Branches -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#3A3A3A] dark:text-[#F2EFE9] mb-3">Fulfillment Hubs</h4>
                    <ul class="space-y-2 text-xs">
                        <li class="flex items-start gap-2">
                            <span class="text-[#FFC107] font-bold">📍</span>
                            <div>
                                <span class="font-bold text-[#3A3A3A] dark:text-[#F2EFE9]">Roxas Hatchery (Main Farm)</span>
                                <p class="text-[#7A7A7A] dark:text-[#8E8B83]">Palao Farmstead, Roxas, Oriental Mindoro</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-[#89B4C4] font-bold">📍</span>
                            <div>
                                <span class="font-bold text-[#3A3A3A] dark:text-[#F2EFE9]">Boracay Island Depot</span>
                                <p class="text-[#7A7A7A] dark:text-[#8E8B83]">Station 2, Malay, Aklan (Order Fulfillment Only)</p>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Col 3: Hardware Architecture -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#3A3A3A] dark:text-[#F2EFE9] mb-3">IoT Telematics Stack</h4>
                    <ul class="space-y-1.5 text-xs text-[#666666] dark:text-[#8E8B83]">
                        <li><span class="font-semibold text-[#3A3A3A] dark:text-[#F2EFE9]">• Visual Stream:</span> ESP32-CAM Live Incubator Feed</li>
                        <li><span class="font-semibold text-[#3A3A3A] dark:text-[#F2EFE9]">• Environment:</span> Sensirion SHT31 Temp & Humidity</li>
                        <li><span class="font-semibold text-[#3A3A3A] dark:text-[#F2EFE9]">• Endpoint:</span> POST /api/telemetry/push</li>
                    </ul>
                </div>

                <!-- Col 4: Quick Links -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#3A3A3A] dark:text-[#F2EFE9] mb-3">Quick Navigation</h4>
                    <ul class="space-y-1.5 text-xs">
                        <li><a href="{{ route('store') }}" class="hover:text-[#FFC107] transition">E-Commerce Storefront</a></li>
                        <li><a href="{{ route('store.about') }}" class="hover:text-[#FFC107] transition">About Farm & Tech</a></li>
                        <li><a href="{{ route('store.cart') }}" class="hover:text-[#FFC107] transition">Cart & Checkout</a></li>
                        @auth
                            @if(auth()->user()->isOwner())
                                <li><a href="{{ route('admin.dashboard') }}" class="text-[#FFC107] font-bold hover:underline">IoT Incubation Dashboard</a></li>
                            @else
                                <li><a href="{{ route('store.orders') }}" class="hover:text-[#FFC107] transition">My Orders</a></li>
                            @endif
                        @endauth
                    </ul>
                </div>
            </div>

            <div class="border-t border-[#D9CBB8]/50 dark:border-[#3A3833] mt-10 pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-[#7A7A7A] dark:text-[#8E8B83] gap-4">
                <p>&copy; 2026 Smart Palao Incubation & Farming Systems. All rights reserved.</p>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#FFC107]"></span>
                    <span>Minimalist Egg Vibe System</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Navigation & Theme Scripts -->
    <script>
        function toggleDarkMode() {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('smart_palao_theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('smart_palao_theme', 'dark');
            }
        }

        function toggleMobileDrawer() {
            const drawer = document.getElementById('mobile-drawer');
            const barsIcon = document.getElementById('hamburger-bars-icon');
            const closeIcon = document.getElementById('hamburger-close-icon');
            if (!drawer) return;

            const isHidden = drawer.classList.contains('hidden');
            if (isHidden) {
                drawer.classList.remove('hidden');
                barsIcon?.classList.add('hidden');
                closeIcon?.classList.remove('hidden');
            } else {
                drawer.classList.add('hidden');
                barsIcon?.classList.remove('hidden');
                closeIcon?.classList.add('hidden');
            }
        }

        // Close mobile drawer on desktop resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                const drawer = document.getElementById('mobile-drawer');
                if (drawer && !drawer.classList.contains('hidden')) {
                    drawer.classList.add('hidden');
                    document.getElementById('hamburger-bars-icon')?.classList.remove('hidden');
                    document.getElementById('hamburger-close-icon')?.classList.add('hidden');
                }
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
