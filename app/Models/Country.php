<?php

namespace App\Models;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'iso_code_2',
        'iso_code_3',
        'numeric_code',
        'phone_code',
        'capital',
        'currency_id',
        'region',
        'subregion',
        'latitude',
        'longitude',
        'emoji',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Get full phone number format
     */
    public function getFullPhoneCodeAttribute()
    {
        return $this->phone_code ? '+' . $this->phone_code : null;
    }
}
