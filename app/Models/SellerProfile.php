<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerProfile extends Model
{
    protected $fillable = [
        'seller_id',
        'business_name',
        'business_description',
        'business_address',
        'business_phone',
        'business_email',
        'tax_id',
        'bank_account',
        'bank_name',
        'account_holder_name',
        'logo',
        'banner',
        'facebook',
        'instagram',
        'twitter',
        'website',
        'is_verified',
        'verification_date'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verification_date' => 'datetime'
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
