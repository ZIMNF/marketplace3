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

    public static function canAccess(): bool
    {
        return auth()->user()->isBuyer();
    }

    public $quantity = [];
    
    public function getSellerProductsProperty()
    {
        return SellerProduct::with('product')->where('stock', '>', 0)->get();
    }

    public function addToCart($sellerProductId)
    {
        $qty = $this->quantity[$sellerProductId] ?? 1;
        if ($qty < 1) return;

        $buyerId = Auth::id();

        // Cek apakah buyer sudah punya item dari seller lain
        $firstCart = CartItem::where('buyer_id', $buyerId)->first();
        if ($firstCart) {
            return;
        }

        CartItem::updateOrCreate(
            [
                'buyer_id' => $buyerId,
                'seller_product_id' => $sellerProductId,
            ],
            [
                'quantity' => \DB::raw("quantity + $qty"),
            ]
        );
        
        // Reset quantity input
        $this->quantity[$sellerProductId] = 1;
    }
}
