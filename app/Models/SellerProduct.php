<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SellerProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'seller_id',
        'price',
        'stock',
    ];

    /**
     * Relasi ke produk master
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relasi ke seller (User dengan role: seller)
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
