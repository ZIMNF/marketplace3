@php use Illuminate\Support\Facades\Storage; @endphp


<x-filament::page>
    <h2 class="text-2xl font-bold mb-4">Produk Tersedia</h2>

    @if(auth()->user()?->role !== 'seller' && auth()->user()?->role !== 'admin')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach ($this->sellerProducts as $item)
            <div class="p-6 border rounded-lg shadow-sm bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700">
                {{-- ✅ Ganti image_url dengan URL dari S3 --}}
                <img src="{{ Storage::disk('s3')->url($item->product->image_url) }}" 
                    alt="{{ $item->product->name }}" 
                    class="w-full h-48 object-cover mb-4 rounded-lg">

                <h3 class="font-bold text-lg mb-2">{{ $item->product->name }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ $item->product->description }}</p>
                
                <div class="flex justify-between items-center mb-4">
                    <span class="font-semibold text-primary-600 text-lg">Rp {{ number_format($item->price) }}</span>
                    <span class="text-sm text-gray-500">Stok: {{ $item->stock }}</span>
                </div>

                <div class="flex gap-2 items-center">
                    <input type="number" min="1" max="{{ $item->stock }}" wire:model.defer="quantity.{{ $item->id }}"
                        class="border rounded-md w-20 text-center bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100" />
                    <x-filament::button 
                        wire:click="addToCart({{ $item->id }})"
                        size="sm">
                        Tambah ke Keranjang
                    </x-filament::button>
                </div>
            </div>
        @endforeach
    </div>
    @endif
</x-filament::page>
