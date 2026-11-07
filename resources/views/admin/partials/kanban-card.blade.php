<div class="egg-card p-4 bg-white dark:bg-[#22201D] border border-[#D9CBB8] dark:border-[#3A3833] rounded-2xl shadow-sm hover:shadow-md transition space-y-3">
    <!-- Card Header -->
    <div class="flex items-start justify-between gap-2">
        <div>
            <span class="font-mono font-black text-xs text-[#3A3A3A] dark:text-[#F2EFE9] block">{{ $order->order_number }}</span>
            <span class="text-[10px] text-gray-400 dark:text-gray-500">{{ $order->created_at->diffForHumans() }}</span>
        </div>
        <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase {{ $order->branch_location === 'Roxas' ? 'bg-[#FFF8E1] dark:bg-[#2A261A] text-[#7A5A00] dark:text-[#FFC107] border border-[#FFC107]/40' : 'bg-[#EBF3F6] dark:bg-[#1B2529] text-[#2C5263] dark:text-[#89B4C4] border border-[#89B4C4]/40' }}">
            📍 {{ $order->branch_location }}
        </span>
    </div>

    <!-- Customer -->
    <div class="text-xs">
        <span class="font-bold text-[#3A3A3A] dark:text-[#F2EFE9] block truncate">{{ $order->customer_name }}</span>
        <span class="text-[11px] text-gray-500 dark:text-gray-400 font-mono">{{ $order->customer_phone }}</span>
        <p class="text-[11px] text-gray-600 dark:text-gray-400 line-clamp-2 mt-0.5">{{ $order->delivery_address }}</p>
    </div>

    <!-- Items Preview -->
    <div class="bg-[#FAF9F6] dark:bg-[#1A1917] p-2 rounded-xl border border-[#D9CBB8]/50 dark:border-[#3A3833] space-y-1 text-[11px]">
        @foreach($order->items as $it)
            <div class="flex items-center justify-between">
                <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $it->quantity }}x {{ $it->size }} Duck Eggs</span>
                <span class="font-mono text-gray-500 dark:text-gray-400">₱{{ number_format($it->subtotal, 2) }}</span>
            </div>
        @endforeach
    </div>

    <!-- Price & Payment -->
    <div class="flex items-center justify-between text-xs pt-1 border-t border-gray-100 dark:border-[#3A3833]">
        <span class="text-[10px] text-gray-500 dark:text-gray-400 uppercase font-semibold">{{ $order->payment_method }}</span>
        <span class="font-black font-mono text-[#3A3A3A] dark:text-[#F2EFE9] text-sm">₱{{ number_format($order->total_amount + $order->delivery_fee, 2) }}</span>
    </div>

    @if($order->notes)
        <p class="text-[10px] text-gray-500 dark:text-gray-400 italic bg-amber-50/50 dark:bg-amber-950/20 p-1.5 rounded-lg border border-amber-100 dark:border-amber-900/40">
            "{{ $order->notes }}"
        </p>
    @endif

    <!-- Kanban Stage Transition Buttons -->
    <div class="pt-2 border-t border-gray-100 dark:border-[#3A3833] flex items-center justify-between gap-1 text-[11px]">
        @if($stage === 'Pending')
            <span></span>
            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="Processing">
                <button type="submit" class="px-3 py-1.5 rounded-xl bg-[#89B4C4] text-white font-bold hover:bg-[#6496A9] transition shadow-sm">
                    Start Packing &rarr;
                </button>
            </form>
        @elseif($stage === 'Processing')
            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="Pending">
                <button type="submit" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 font-semibold">
                    &larr; Revert
                </button>
            </form>
            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="Shipped">
                <button type="submit" class="px-3 py-1.5 rounded-xl bg-purple-600 text-white font-bold hover:bg-purple-700 transition shadow-sm">
                    Dispatch &rarr;
                </button>
            </form>
        @elseif($stage === 'Shipped')
            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="Processing">
                <button type="submit" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 font-semibold">
                    &larr; Revert
                </button>
            </form>
            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="Delivered">
                <button type="submit" class="px-3 py-1.5 rounded-xl bg-emerald-600 text-white font-bold hover:bg-emerald-700 transition shadow-sm">
                    Delivered &check;
                </button>
            </form>
        @elseif($stage === 'Delivered')
            <span class="text-emerald-600 dark:text-emerald-400 font-bold text-[10px] flex items-center gap-1">
                <span>✓ Completed</span>
            </span>
            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="Shipped">
                <button type="submit" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-[10px]">
                    Reopen
                </button>
            </form>
        @endif
    </div>
</div>
