@extends('layouts.app')

@section('title', 'Register - Smart Palao')

@section('content')
<div class="max-w-md mx-auto my-12 px-4">
    <div class="egg-card p-8 bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] shadow-md rounded-3xl">
        <div class="text-center mb-6">
            <div class="w-16 h-16 rounded-full overflow-hidden mx-auto shadow-sm border border-[#D9CBB8] dark:border-[#3A3833] mb-3 p-1 bg-[#FAF9F6] dark:bg-[#1C1B19]">
                <img src="{{ asset('images/smart-palao-logo.png') }}" alt="Smart Palao Logo" class="w-full h-full object-contain rounded-full">
            </div>
            <h1 class="text-2xl font-black text-[#3A3A3A] dark:text-[#F2EFE9] tracking-tight">Create Account</h1>
            <p class="text-xs text-[#7A7A7A] dark:text-[#8E8B83] mt-1 font-medium">Join the Smart Palao farmstead network</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Role Selector -->
            <div>
                <label class="block text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] uppercase tracking-wider mb-1.5">Account Role</label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="flex flex-col p-3 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] cursor-pointer hover:border-[#FFC107] transition bg-[#FAF9F6] dark:bg-[#1A1917]">
                        <div class="flex items-center gap-2">
                            <input type="radio" name="role" value="buyer" checked class="text-[#FFC107] focus:ring-[#FFC107]">
                            <span class="text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9]">🛒 Buyer</span>
                        </div>
                        <span class="text-[10px] text-gray-500 dark:text-gray-400 mt-1 pl-5">Fresh egg orders</span>
                    </label>

                    <label class="flex flex-col p-3 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] cursor-pointer hover:border-[#FFC107] transition bg-[#FAF9F6] dark:bg-[#1A1917]">
                        <div class="flex items-center gap-2">
                            <input type="radio" name="role" value="owner" class="text-[#FFC107] focus:ring-[#FFC107]">
                            <span class="text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9]">👑 Owner</span>
                        </div>
                        <span class="text-[10px] text-gray-500 dark:text-gray-400 mt-1 pl-5">Full IoT & hardware</span>
                    </label>
                </div>
            </div>

            <div>
                <label for="name" class="block text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] uppercase tracking-wider mb-1">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] bg-white dark:bg-[#1A1917] text-[#3A3A3A] dark:text-[#F2EFE9] focus:border-[#FFC107] focus:ring-2 focus:ring-[#FFC107]/20 outline-none text-sm transition">
                @error('name')
                    <p class="text-xs text-red-600 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] uppercase tracking-wider mb-1">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2.5 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] bg-white dark:bg-[#1A1917] text-[#3A3A3A] dark:text-[#F2EFE9] focus:border-[#FFC107] focus:ring-2 focus:ring-[#FFC107]/20 outline-none text-sm transition">
                @error('email')
                    <p class="text-xs text-red-600 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="password" class="block text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] uppercase tracking-wider mb-1">Password</label>
                    <input type="password" id="password" name="password" required class="w-full px-4 py-2.5 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] bg-white dark:bg-[#1A1917] text-[#3A3A3A] dark:text-[#F2EFE9] focus:border-[#FFC107] focus:ring-2 focus:ring-[#FFC107]/20 outline-none text-sm transition">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] uppercase tracking-wider mb-1">Confirm</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full px-4 py-2.5 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] bg-white dark:bg-[#1A1917] text-[#3A3A3A] dark:text-[#F2EFE9] focus:border-[#FFC107] focus:ring-2 focus:ring-[#FFC107]/20 outline-none text-sm transition">
                </div>
            </div>
            @error('password')
                <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
            @enderror

            <div>
                <label for="phone" class="block text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] uppercase tracking-wider mb-1">Contact Phone</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+63 9XX XXX XXXX" class="w-full px-4 py-2.5 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] bg-white dark:bg-[#1A1917] text-[#3A3A3A] dark:text-[#F2EFE9] focus:border-[#FFC107] focus:ring-2 focus:ring-[#FFC107]/20 outline-none text-sm transition">
            </div>

            <div>
                <label for="address" class="block text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] uppercase tracking-wider mb-1">Delivery Address</label>
                <textarea id="address" name="address" rows="2" placeholder="Barangay, Municipality, Province" class="w-full px-4 py-2.5 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] bg-white dark:bg-[#1A1917] text-[#3A3A3A] dark:text-[#F2EFE9] focus:border-[#FFC107] focus:ring-2 focus:ring-[#FFC107]/20 outline-none text-sm transition">{{ old('address') }}</textarea>
            </div>

            <button type="submit" class="w-full egg-btn-primary py-3 font-bold text-sm rounded-xl mt-4">
                Create Account
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-[#7A7A7A] dark:text-[#8E8B83]">
            Already have an account? 
            <a href="{{ route('login') }}" class="font-bold text-[#3A3A3A] dark:text-[#FFC107] hover:underline">Log in here</a>
        </div>
    </div>
</div>
@endsection
