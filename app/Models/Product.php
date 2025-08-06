<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'name',
        'image_url',
        'description',
        'created_by',
    ];

    public function sellerProducts(): HasMany
    {
        return $this->hasMany(SellerProduct::class);
    }

    public function sellers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'seller_products')
            ->withPivot('price', 'stock')
            ->withTimestamps();
    }

    /**
     * Relasi ke user yang membuat produk (admin)
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
