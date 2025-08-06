<?php

namespace App\Filament\Pages;

use App\Models\Order;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class OrderHistory extends Page
{
    protected static string $view = 'filament.pages.order-history';
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'History Pesanan';
    protected static ?string $navigationGroup = 'Buyer';

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'buyer';
    }

    public function getOrdersProperty()
    {
        return Order::with(['seller', 'orderItems.sellerProduct.product'])
            ->where('buyer_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getStatusColor($status)
    {
        $baseClasses = 'text-sm font-semibold px-2 py-1 rounded-md ring-1 ring-inset';

        return match ($status) {
            'pending'         => "$baseClasses bg-yellow-300 text-black dark:bg-yellow-500 dark:text-white",
            'confirmed'       => "$baseClasses bg-blue-300 text-black dark:bg-blue-500 dark:text-white",
            'ready_to_pickup' => "$baseClasses bg-purple-300 text-black dark:bg-purple-500 dark:text-white",
            'done'            => "$baseClasses bg-green-300 text-black dark:bg-green-500 dark:text-white",
            'cancelled'       => "$baseClasses bg-red-300 text-black dark:bg-red-500 dark:text-white",
            default           => "$baseClasses bg-gray-300 text-black dark:bg-gray-600 dark:text-white",
        };
    }

    public function getStatusLabel($status)
    {
        return match ($status) {
            'pending'         => 'Menunggu Konfirmasi',
            'confirmed'       => 'Dikonfirmasi',
            'ready_to_pickup' => 'Siap Diambil',
            'done'            => 'Selesai',
            'cancelled'       => 'Dibatalkan',
            default           => ucfirst($status),
        };
    }
}
