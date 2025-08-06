<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AutoCancelOrder extends Command
{
    protected $signature = 'order:auto-cancel';
    protected $description = 'Batalkan order yang belum diproses selama 5 menit';

    public function handle()
    {
        $this->info('Starting auto-cancel process...');
        
        // Set waktu kadaluarsa menjadi 5 menit
        $expiryTime = Carbon::now()->subMinutes(5);
        
        $this->info('Checking orders older than: ' . $expiryTime->format('Y-m-d H:i:s'));
        
        // Cari order yang pending dan sudah lebih dari 5 menit
        $expiredOrders = Order::where('status', 'pending')
            ->where('created_at', '<=', $expiryTime)
            ->get();

        $this->info('Found ' . $expiredOrders->count() . ' orders to cancel');
        
        $cancelledCount = 0;
        
        foreach ($expiredOrders as $order) {
            $oldStatus = $order->status;
            $order->update(['status' => 'cancelled']);
            $cancelledCount++;
            
            // Log setiap pembatalan untuk debugging
            Log::info("Order #{$order->id} dibatalkan otomatis karena tidak diproses dalam 5 menit", [
                'order_id' => $order->id,
                'buyer_id' => $order->buyer_id,
                'seller_id' => $order->seller_id,
                'old_status' => $oldStatus,
                'new_status' => 'cancelled',
                'created_at' => $order->created_at,
                'cancelled_at' => now(),
                'expiry_time' => $expiryTime->format('Y-m-d H:i:s')
            ]);
            
            $this->info("Cancelled order #{$order->id} (created: {$order->created_at})");
        }

        $this->info("Auto-cancel completed. {$cancelledCount} orders cancelled (expired > 5 minutes).");
        
        return Command::SUCCESS;
    }
}
