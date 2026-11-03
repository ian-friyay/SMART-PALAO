@extends('layouts.app')

@section('title', 'Log In - Smart Palao')

@section('content')
<div class="max-w-md mx-auto my-12 px-4">
    <div class="egg-card p-8 bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] shadow-md rounded-3xl">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-full overflow-hidden mx-auto shadow-sm border border-[#D9CBB8] dark:border-[#3A3833] mb-3 p-1 bg-[#FAF9F6] dark:bg-[#1C1B19]">
                <img src="{{ asset('images/smart-palao-logo.png') }}" alt="Smart Palao Logo" class="w-full h-full object-contain rounded-full">
            </div>
            <h1 class="text-2xl font-black text-[#3A3A3A] dark:text-[#F2EFE9] tracking-tight">Welcome Back</h1>
            <p class="text-xs text-[#7A7A7A] dark:text-[#8E8B83] mt-1 font-medium">Log in to access your incubation controls or egg orders</p>
        </div>

        <!-- 1-Click Fast Evaluator Buttons -->
        <div class="mb-6 p-4 rounded-2xl bg-[#F4F1EA] dark:bg-[#1A1917] border border-[#D9CBB8] dark:border-[#3A3833] space-y-2">
            <div class="flex items-center gap-1.5 text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9]">
                <span class="text-[#FFC107]">⚡</span>
                <span>Fast Login as Evaluator:</span>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <a href="{{ route('auth.quickLogin', 'owner') }}" class="px-3 py-2 text-center rounded-xl bg-[#FFC107] text-[#1F1F1F] font-bold text-xs hover:bg-[#E5AC00] transition shadow-sm">
                    👑 Login as Owner
                </a>
                <a href="{{ route('auth.quickLogin', 'buyer') }}" class="px-3 py-2 text-center rounded-xl bg-[#89B4C4] text-white font-bold text-xs hover:bg-[#6496A9] transition shadow-sm">
                    🛒 Login as Buyer
                </a>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] uppercase tracking-wider mb-1">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email', 'owner@smartpalao.com') }}" required class="w-full px-4 py-2.5 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] bg-white dark:bg-[#1A1917] text-[#3A3A3A] dark:text-[#F2EFE9] focus:border-[#FFC107] focus:ring-2 focus:ring-[#FFC107]/20 outline-none text-sm transition">
                @error('email')
                    <p class="text-xs text-red-600 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] uppercase tracking-wider mb-1">Password</label>
                <input type="password" id="password" name="password" value="password123" required class="w-full px-4 py-2.5 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] bg-white dark:bg-[#1A1917] text-[#3A3A3A] dark:text-[#F2EFE9] focus:border-[#FFC107] focus:ring-2 focus:ring-[#FFC107]/20 outline-none text-sm transition">
                @error('password')
                    <p class="text-xs text-red-600 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer font-medium text-[#5A5A5A] dark:text-[#A8A49C]">
                    <input type="checkbox" name="remember" class="rounded border-[#D9CBB8] text-[#FFC107] focus:ring-[#FFC107]">
                    <span>Remember me</span>
                </label>
                <span class="text-xs text-[#7A7A7A] dark:text-[#8E8B83]">Demo pass: <code class="bg-gray-100 dark:bg-[#1A1917] px-1 py-0.5 rounded font-mono">password123</code></span>
            </div>

            <button type="submit" class="w-full egg-btn-primary py-3 font-bold text-sm rounded-xl mt-2">
                Sign In to Smart Palao
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-[#7A7A7A] dark:text-[#8E8B83]">
            Don't have an account? 
            <a href="{{ route('register') }}" class="font-bold text-[#3A3A3A] dark:text-[#FFC107] hover:underline">Create one now</a>
        </div>
    </div>
</div>
@endsection
