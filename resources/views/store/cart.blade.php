@extends('layouts.app')

@section('title', 'Shopping Cart & Checkout - Smart Palao')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    <div class="flex items-center justify-between pb-6 border-b border-[#D9CBB8] dark:border-[#3A3833]">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-[#FFC107]">Fresh Farm Checkout</span>
            <h1 class="text-3xl font-black text-[#3A3A3A] dark:text-[#F2EFE9] tracking-tight mt-0.5">Your Egg Cart</h1>
        </div>
        <a href="{{ route('store') }}" class="text-xs font-bold text-[#5A5A5A] dark:text-[#A8A49C] hover:text-[#FFC107] flex items-center gap-1">
            <span>&larr; Continue Shopping</span>
        </a>
    </div>

    <!-- Empty Cart State -->
    <div id="cart-empty-state" class="egg-card p-12 bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] rounded-3xl text-center space-y-4 hidden">
        <div class="w-20 h-20 rounded-full bg-[#FFF8E1] dark:bg-[#2A261A] text-[#FFC107] mx-auto flex items-center justify-center text-4xl border border-[#FFC107]">
            🥚
        </div>
        <h3 class="text-xl font-black text-[#3A3A3A] dark:text-[#F2EFE9]">Your basket is currently empty</h3>
        <p class="text-xs text-[#7A7A7A] dark:text-[#8E8B83] max-w-sm mx-auto">
            Explore our fresh Large, Medium, and Small duck eggs harvested and incubated at our Roxas main farm and Boracay depot.
        </p>
        <div>
            <a href="{{ route('store') }}" class="egg-btn-primary px-6 py-3 rounded-xl font-bold text-xs inline-flex items-center gap-2">
                <span>Browse Egg Catalog</span>
            </a>
        </div>
    </div>

    <!-- Active Cart & Checkout Grid -->
    <div id="cart-active-layout" class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- Left: Cart Items List (7 cols) -->
        <div class="lg:col-span-7 space-y-4">
            <div class="egg-card p-6 bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] rounded-3xl shadow-sm">
                <div class="flex items-center justify-between pb-3 border-b border-[#D9CBB8] dark:border-[#3A3833]">
                    <h2 class="text-base font-extrabold text-[#3A3A3A] dark:text-[#F2EFE9]">Egg Trays & Dozens</h2>
                    <span id="cart-items-count-label" class="text-xs text-gray-500 dark:text-gray-400 font-bold">0 items</span>
                </div>

                <div id="cart-items-list" class="divide-y divide-[#D9CBB8]/40 dark:divide-[#3A3833] mt-3">
                    <!-- Dynamic Cart Items injected by JavaScript -->
                </div>

                <div class="pt-4 flex items-center justify-between text-xs">
                    <button onclick="clearEntireCart()" class="text-red-500 hover:text-red-700 font-bold">
                        Clear Basket
                    </button>
                    <span class="text-gray-400 dark:text-gray-500">All prices in Philippine Peso (PHP)</span>
                </div>
            </div>

            <!-- Branch Verification Note -->
            <div class="p-4 rounded-2xl bg-[#FFF8E1] dark:bg-[#2A261A] border border-[#FFC107]/50 text-xs text-[#3A3A3A] dark:text-[#F2EFE9] flex items-start gap-3">
                <span class="text-xl">📍</span>
                <div>
                    <span class="font-bold">Fulfillment Hub: {{ $selectedBranch }} Branch</span>
                    <p class="text-[11px] text-[#5A5A5A] dark:text-[#A8A49C] mt-0.5">
                        Eggs will be picked, sanitized, and packed from our local {{ $selectedBranch }} facility.
                    </p>
                </div>
            </div>
        </div>

        <!-- Right: Delivery Details & Order Summary Form (5 cols) -->
        <div class="lg:col-span-5 space-y-4">
            <div class="egg-card p-6 bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] rounded-3xl shadow-sm">
                <h2 class="text-base font-extrabold text-[#3A3A3A] dark:text-[#F2EFE9] pb-3 border-b border-[#D9CBB8] dark:border-[#3A3833]">
                    Delivery & Checkout Information
                </h2>

                <form id="checkout-form" onsubmit="processCheckout(event)" class="mt-4 space-y-4">
                    @csrf
                    <input type="hidden" name="branch_location" value="{{ $selectedBranch }}">
                    <input type="hidden" id="cart-items-json" name="items_json">

                    <div>
                        <label class="block text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] uppercase tracking-wider mb-1">Full Name</label>
                        <input type="text" name="customer_name" value="{{ auth()->user()->name ?? old('customer_name') }}" required placeholder="e.g. Juan Dela Cruz" class="w-full px-3 py-2 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] bg-white dark:bg-[#1A1917] text-[#3A3A3A] dark:text-[#F2EFE9] text-xs font-bold focus:border-[#FFC107] outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] uppercase tracking-wider mb-1">Email</label>
                            <input type="email" name="customer_email" value="{{ auth()->user()->email ?? old('customer_email') }}" required placeholder="your@email.com" class="w-full px-3 py-2 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] bg-white dark:bg-[#1A1917] text-[#3A3A3A] dark:text-[#F2EFE9] text-xs font-bold focus:border-[#FFC107] outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] uppercase tracking-wider mb-1">Contact Phone</label>
                            <input type="text" name="customer_phone" value="{{ auth()->user()->phone ?? '+63 928 555 7788' }}" required placeholder="+63 9XX XXX XXXX" class="w-full px-3 py-2 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] bg-white dark:bg-[#1A1917] text-[#3A3A3A] dark:text-[#F2EFE9] text-xs font-bold focus:border-[#FFC107] outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] uppercase tracking-wider mb-1">Delivery Address</label>
                        <textarea name="delivery_address" rows="2" required placeholder="House / Hotel name, Street, Barangay, Postal Code" class="w-full px-3 py-2 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] bg-white dark:bg-[#1A1917] text-[#3A3A3A] dark:text-[#F2EFE9] text-xs focus:border-[#FFC107] outline-none">{{ auth()->user()->address ?? '' }}</textarea>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label class="block text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] uppercase tracking-wider mb-1.5">Payment Method</label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="p-2.5 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] flex flex-col items-center text-center cursor-pointer hover:border-[#FFC107] bg-[#FAF9F6] dark:bg-[#1A1917]">
                                <input type="radio" name="payment_method" value="Cash on Delivery" checked class="text-[#FFC107] focus:ring-[#FFC107] mb-1">
                                <span class="text-[11px] font-bold text-[#3A3A3A] dark:text-[#F2EFE9]">COD</span>
                                <span class="text-[9px] text-gray-500 dark:text-gray-400">Upon drop</span>
                            </label>
                            <label class="p-2.5 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] flex flex-col items-center text-center cursor-pointer hover:border-[#FFC107] bg-[#FAF9F6] dark:bg-[#1A1917]">
                                <input type="radio" name="payment_method" value="GCash" class="text-[#FFC107] focus:ring-[#FFC107] mb-1">
                                <span class="text-[11px] font-bold text-[#3A3A3A] dark:text-[#F2EFE9]">GCash</span>
                                <span class="text-[9px] text-gray-500 dark:text-gray-400">E-Wallet</span>
                            </label>
                            <label class="p-2.5 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] flex flex-col items-center text-center cursor-pointer hover:border-[#FFC107] bg-[#FAF9F6] dark:bg-[#1A1917]">
                                <input type="radio" name="payment_method" value="Bank Transfer" class="text-[#FFC107] focus:ring-[#FFC107] mb-1">
                                <span class="text-[11px] font-bold text-[#3A3A3A] dark:text-[#F2EFE9]">Bank</span>
                                <span class="text-[9px] text-gray-500 dark:text-gray-400">Instapay</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#3A3A3A] dark:text-[#F2EFE9] uppercase tracking-wider mb-1">Special Instructions (Optional)</label>
                        <input type="text" name="notes" placeholder="e.g. Leave with reception, handle with care" class="w-full px-3 py-2 rounded-xl border border-[#D9CBB8] dark:border-[#3A3833] bg-white dark:bg-[#1A1917] text-[#3A3A3A] dark:text-[#F2EFE9] text-xs focus:border-[#FFC107] outline-none">
                    </div>

                    <!-- Cost Breakdown -->
                    <div class="pt-4 border-t border-[#D9CBB8]/40 dark:border-[#3A3833] space-y-2 text-xs">
                        <div class="flex items-center justify-between text-gray-600 dark:text-gray-400">
                            <span>Eggs Subtotal:</span>
                            <span id="checkout-subtotal" class="font-mono font-bold text-[#3A3A3A] dark:text-[#F2EFE9]">₱0.00</span>
                        </div>
                        <div class="flex items-center justify-between text-gray-600 dark:text-gray-400">
                            <span>Delivery Fee ({{ $selectedBranch }}):</span>
                            <span id="checkout-delivery-fee" class="font-mono font-bold text-[#3A3A3A] dark:text-[#F2EFE9]">
                                ₱{{ $selectedBranch === 'Boracay' ? '75.00' : '50.00' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-sm font-extrabold text-[#3A3A3A] dark:text-[#F2EFE9] pt-2 border-t border-[#D9CBB8] dark:border-[#3A3833]">
                            <span>Grand Total:</span>
                            <span id="checkout-total" class="text-xl font-black font-mono text-[#3A3A3A] dark:text-[#F2EFE9]">₱0.00</span>
                        </div>
                    </div>

                    <button type="submit" id="place-order-btn" class="w-full egg-btn-primary py-3 rounded-xl font-bold text-sm shadow-md mt-2">
                        Confirm & Place Duck Egg Order
                    </button>
                </form>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    const currentBranch = "{{ $selectedBranch }}";
    const deliveryFee = currentBranch === 'Boracay' ? 75.00 : 50.00;

    document.addEventListener('DOMContentLoaded', () => {
        renderCart();
        window.addEventListener('cart-updated', renderCart);
    });

    function renderCart() {
        const items = window.SmartPalao.cart.getItems();
        const emptyState = document.getElementById('cart-empty-state');
        const activeLayout = document.getElementById('cart-active-layout');
        const listContainer = document.getElementById('cart-items-list');

        if (!items || items.length === 0) {
            emptyState.classList.remove('hidden');
            activeLayout.classList.add('hidden');
            return;
        }

        emptyState.classList.add('hidden');
        activeLayout.classList.remove('hidden');

        document.getElementById('cart-items-count-label').textContent = `${items.length} product(s) selected`;

        listContainer.innerHTML = '';
        let subtotal = 0;

        items.forEach(item => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;

            const div = document.createElement('div');
            div.className = 'py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3';
            div.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-[#FFF8E1] dark:bg-[#2A261A] border border-[#FFC107]/40 flex items-center justify-center text-2xl shrink-0">
                        ${item.size === 'Large' ? '🥚' : (item.size === 'Medium' ? '🍳' : '🐣')}
                    </div>
                    <div>
                        <h4 class="font-extrabold text-xs sm:text-sm text-[#3A3A3A] dark:text-[#F2EFE9]">${item.size} Duck Eggs</h4>
                        <span class="text-[11px] text-gray-500 dark:text-gray-400 font-mono">₱${item.price.toFixed(2)} / dozen</span>
                        <span class="text-[10px] text-[#89B4C4] font-bold block">📍 ${item.branch || currentBranch} Stock</span>
                    </div>
                </div>

                <div class="flex items-center justify-between sm:justify-end gap-3 sm:gap-4 pt-2 sm:pt-0 border-t sm:border-0 border-gray-100 dark:border-[#2F2D29]">
                    <!-- Qty Selector -->
                    <div class="flex items-center border border-[#D9CBB8] dark:border-[#3A3833] rounded-xl bg-[#FAF9F6] dark:bg-[#1A1917] overflow-hidden">
                        <button onclick="changeQty(${item.id}, ${item.quantity - 1})" class="px-3 py-1.5 text-xs font-bold text-gray-600 dark:text-gray-300 hover:bg-[#EBE5D8] dark:hover:bg-[#2D2B27] transition">&minus;</button>
                        <span class="px-3 py-1.5 text-xs font-mono font-bold text-[#3A3A3A] dark:text-[#F2EFE9]">${item.quantity}</span>
                        <button onclick="changeQty(${item.id}, ${item.quantity + 1})" class="px-3 py-1.5 text-xs font-bold text-gray-600 dark:text-gray-300 hover:bg-[#EBE5D8] dark:hover:bg-[#2D2B27] transition">&plus;</button>
                    </div>

                    <!-- Item Total -->
                    <div class="text-right min-w-[70px]">
                        <span class="text-xs sm:text-sm font-black font-mono text-[#3A3A3A] dark:text-[#F2EFE9]">₱${itemTotal.toFixed(2)}</span>
                    </div>

                    <!-- Remove -->
                    <button onclick="removeItem(${item.id})" class="text-gray-400 hover:text-red-600 font-bold text-sm p-1.5" title="Remove item">
                        ✕
                    </button>
                </div>
            `;
            listContainer.appendChild(div);
        });

        const total = subtotal + deliveryFee;
        document.getElementById('checkout-subtotal').textContent = `₱${subtotal.toFixed(2)}`;
        document.getElementById('checkout-total').textContent = `₱${total.toFixed(2)}`;
    }

    function changeQty(productId, qty) {
        window.SmartPalao.cart.updateQty(productId, qty);
    }

    function removeItem(productId) {
        window.SmartPalao.cart.removeItem(productId);
    }

    function clearEntireCart() {
        if (!confirm('Clear all items from your basket?')) return;
        window.SmartPalao.cart.clear();
    }

    function processCheckout(e) {
        e.preventDefault();
        const form = e.target;
        const items = window.SmartPalao.cart.getItems();

        if (items.length === 0) {
            alert('Your basket is empty!');
            return;
        }

        const btn = document.getElementById('place-order-btn');
        btn.disabled = true;
        btn.textContent = 'Processing Egg Order...';

        const payload = {
            branch_location: form.branch_location.value,
            customer_name: form.customer_name.value,
            customer_email: form.customer_email.value,
            customer_phone: form.customer_phone.value,
            delivery_address: form.delivery_address.value,
            payment_method: form.payment_method.value,
            notes: form.notes.value,
            items: items.map(i => ({
                product_id: i.id,
                quantity: i.quantity,
            })),
        };

        fetch('/checkout', {
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
            btn.textContent = 'Confirm & Place Duck Egg Order';
            if (data.success) {
                window.SmartPalao.cart.clear();
                window.location.href = `/order-confirmation/${data.order_number}`;
            } else {
                alert(data.message || 'Error creating order. Please check stock levels.');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.textContent = 'Confirm & Place Duck Egg Order';
            console.error('Order checkout error:', err);
            alert('Failed to place order. Please try again.');
        });
    }
</script>
@endpush
