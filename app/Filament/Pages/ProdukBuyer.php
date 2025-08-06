<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\SellerProduct;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class ProdukBuyer extends Page
{
    protected static string $view = 'filament.pages.produk-buyer';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Browse Produk';
    protected static ?string $navigationGroup = 'Buyer';

    public $quantity = [];

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->role === 'buyer';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->role === 'buyer';
    }

    public function getSellerProductsProperty()
    {
        return SellerProduct::with('product')->where('stock', '>', 0)->get();
    }

    public function addToCart($sellerProductId)
    {
        $qty = $this->quantity[$sellerProductId] ?? 1;
        if ($qty < 1) return;

        $buyerId = Auth::id();
        $newProduct = SellerProduct::findOrFail($sellerProductId);
        $newSellerId = $newProduct->seller_id;

        // Ambil semua item di keranjang user
        $existingCart = CartItem::with('sellerProduct')->where('buyer_id', $buyerId)->get();

        // Jika ada item dan seller-nya beda, tolak
        if ($existingCart->isNotEmpty()) {
            $existingSellerId = $existingCart->first()->sellerProduct->seller_id;

            if ($existingSellerId !== $newSellerId) {
                $this->dispatch('notify', type: 'warning', message: 'Kamu hanya bisa menambahkan produk dari satu seller.');
                return;
            }
        }

        // Tambahkan atau update jumlah produk
        CartItem::updateOrCreate(
            [
                'buyer_id' => $buyerId,
                'seller_product_id' => $sellerProductId,
            ],
            [
                'quantity' => \DB::raw("quantity + $qty"),
            ]
        );

        // Notifikasi sukses
        $this->dispatch('notify', type: 'success', message: 'Produk ditambahkan ke keranjang.');

        // Reset input quantity
        $this->quantity[$sellerProductId] = 1;
    }
}
