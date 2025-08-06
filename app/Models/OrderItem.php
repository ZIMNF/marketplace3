<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'seller_product_id',
        'quantity',
        'price_at_order_time',
        'subtotal',
    ];

    protected $casts = [
        'price_at_order_time' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'quantity' => 'integer',
    ];

    /**
     * Relasi ke order
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relasi ke seller product
     */
    public function sellerProduct(): BelongsTo
    {
        return $this->belongsTo(SellerProduct::class);
    }

    /**
     * Relasi ke produk (melalui sellerProduct)
     */
    public function product()
    {
        return $this->sellerProduct?->product();
    }
}
