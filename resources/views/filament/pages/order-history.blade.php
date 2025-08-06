<x-filament-panels::page class="mt-8">
    <div class="pb-12">
        @if ($this->orders->isEmpty())
            <div class="text-center py-16">
                <div class="mx-auto w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                    <x-heroicon-o-clock class="w-10 h-10 text-gray-400 dark:text-gray-500" />
                </div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Belum ada pesanan</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Anda belum pernah melakukan pemesanan.</p>
            </div>
        @else
            @foreach ($this->orders as $order)
                <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden mt-8">
                    <!-- Order Header -->
                    <div class="bg-gray-50 dark:bg-gray-800 px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex justify-between items-start md:items-center flex-col md:flex-row gap-2">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Order #{{ $order->id }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Penjual: <span class="font-semibold text-gray-900 dark:text-white">{{ $order->seller->name }}</span></p>
                        </div>
                        <div class="text-right space-y-2">
                            <span class="{{ $this->getStatusColor($order->status) }}">
                                {{ $this->getStatusLabel($order->status) }}
                            </span>
                            <p class="text-xl font-bold text-gray-900 dark:text-white">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="px-6 pt-5 pb-6 space-y-5">
                        @foreach ($order->orderItems as $item)
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center">
                                        <x-heroicon-o-cube class="w-6 h-6 text-gray-400 dark:text-gray-500" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $item->sellerProduct->product->name }}
                                        </p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $item->quantity }} x Rp {{ number_format($item->price_at_order_time, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    <!-- Timeline -->
                    @if ($order->status !== 'cancelled')
                        <div class="bg-gray-50 dark:bg-gray-800 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex flex-wrap justify-between gap-y-2 text-sm">
                                @php
                                    $steps = [
                                        ['label' => 'Pesanan Dibuat', 'condition' => in_array($order->status, ['pending', 'confirmed', 'ready_to_pickup', 'done'])],
                                        ['label' => 'Dikonfirmasi',    'condition' => in_array($order->status, ['confirmed', 'ready_to_pickup', 'done'])],
                                        ['label' => 'Siap Diambil',    'condition' => in_array($order->status, ['ready_to_pickup', 'done'])],
                                        ['label' => 'Selesai',         'condition' => $order->status === 'done'],
                                    ];
                                @endphp

                                @foreach ($steps as $step)
                                    <div class="flex items-center gap-2">
                                        <div class="w-3 h-3 rounded-full {{ $step['condition'] ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600' }}"></div>
                                        <span class="{{ $step['condition'] ? 'text-green-700 dark:text-green-300' : 'text-gray-500 dark:text-gray-400' }}">{{ $step['label'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        @endif
    </div>
</x-filament-panels::page>
