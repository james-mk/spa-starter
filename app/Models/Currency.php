<?php

namespace App\Models;

use App\Models\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'symbol',
        'decimal_mark',
        'thousands_separator',
        'decimal_places',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'decimal_places' => 'integer',
    ];

    public function countries()
    {
        return $this->hasMany(Country::class);
    }

    /**
     * Format an amount in this currency
     */
    public function format($amount)
    {
        $formatted = number_format(
            $amount,
            $this->decimal_places,
            $this->decimal_mark,
            $this->thousands_separator
        );

        return $this->symbol . $formatted;
    }
}
