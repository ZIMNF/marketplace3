<x-filament::page>
    <h2 class="text-2xl font-bold mb-4 text-white dark:text-white">Keranjang Anda</h2>

    @if ($this->items->isEmpty())
        <div class="text-gray-600 dark:text-gray-300">Keranjang kosong.</div>
    @else
        <table class="w-full table-auto border mb-4 bg-white dark:bg-gray-800">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="p-2 text-left text-black dark:text-white">Produk</th>
                    <th class="p-2 text-black dark:text-white">Harga</th>
                    <th class="p-2 text-black dark:text-white">Qty</th>
                    <th class="p-2 text-black dark:text-white">Subtotal</th>
                    <th class="p-2 text-black dark:text-white">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->items as $item)
                    <tr class="border-t">
                        <td class="p-2 text-black dark:text-white">{{ $item->sellerProduct->product->name }}</td>
                        <td class="p-2 text-black dark:text-white">Rp {{ number_format($item->sellerProduct->price) }}</td>
                        <td class="p-2 text-center text-black dark:text-white">{{ $item->quantity }}</td>
                        <td class="p-2 text-black dark:text-white">Rp {{ number_format($item->quantity * $item->sellerProduct->price) }}</td>
                        <td class="p-2">
                            <button 
                                wire:click="removeItem({{ $item->id }})" 
                                wire:confirm="Apakah Anda yakin ingin menghapus produk ini dari keranjang?"
                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                @endforeach
                <tr class="font-bold border-t">
                    <td colspan="4" class="p-2 text-right text-black dark:text-white">Total:</td>
                    <td class="p-2 text-black dark:text-white">Rp {{ number_format($this->total) }}</td>
                </tr>
            </tbody>
        </table>

        <x-filament::button wire:click="checkout">
            Checkout
        </x-filament::button>
    @endif
</x-filament::page>
