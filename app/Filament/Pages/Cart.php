<?php

namespace App\Filament\Pages;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Cart extends Page
{
    protected static string $view = 'filament.pages.cart';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Keranjang';
    protected static ?string $navigationGroup = 'Buyer';

    // ✅ Mencegah akses oleh selain buyer
    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->role === 'buyer';
    }

    // ✅ Menyembunyikan dari sidebar jika bukan buyer
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->role === 'buyer';
    }

    public function getItemsProperty()
    {
        return CartItem::with('sellerProduct.product')
            ->where('buyer_id', Auth::id())
            ->get();
    }

    public function getTotalProperty()
    {
        return $this->items->sum(fn ($item) => $item->sellerProduct->price * $item->quantity);
    }

    public function removeItem($itemId)
    {
        $item = CartItem::where('id', $itemId)
            ->where('buyer_id', Auth::id())
            ->first();

        if ($item) {
            $item->delete();
            $this->dispatch('notify', type: 'success', message: 'Produk berhasil dihapus dari keranjang.');
        }
    }

    public function checkout()
    {
        $buyerId = Auth::id();
        $cartItems = CartItem::where('buyer_id', $buyerId)->get();

        if ($cartItems->isEmpty()) {
            $this->dispatch('notify', type: 'warning', message: 'Keranjang kosong.');
            return;
        }

        $itemsBySeller = $cartItems->groupBy(fn ($item) => $item->sellerProduct->seller_id);

        DB::transaction(function () use ($buyerId, $itemsBySeller) {
            foreach ($itemsBySeller as $sellerId => $items) {
                $order = Order::create([
                    'buyer_id' => $buyerId,
                    'seller_id' => $sellerId,
                    'status' => 'pending',
                ]);

                foreach ($items as $item) {
                    $price = $item->sellerProduct->price;
                    $qty = $item->quantity;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'seller_product_id' => $item->seller_product_id,
                        'quantity' => $qty,
                        'price_at_order_time' => $price,
                        'subtotal' => $qty * $price,
                    ]);
                }
            }

            CartItem::where('buyer_id', $buyerId)->delete();
        });

        $this->dispatch('notify', type: 'success', message: 'Checkout berhasil.');
    }
}
