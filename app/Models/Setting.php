<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    protected $fillable = [
        'company_name',
        'company_email',
        'company_phone',
        'company_address',
        'company_logo',
        'theme',
        'primary_color',
        'secondary_color',
        'timezone',
        'date_format',
        'time_format',
    ];

    protected $appends = ['company_logo_url', 'company_initials'];

    public function getCompanyLogoUrlAttribute()
    {
        if ($this->company_logo && file_exists(public_path($this->company_logo))) {
            return url($this->company_logo);
        }
        return null;
    }

    public function getCompanyInitialsAttribute()
    {
        if ($this->company_name) {
            $words = explode(' ', $this->company_name);
            if (count($words) >= 2) {
                return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
            }
            return strtoupper(substr($this->company_name, 0, 2));
        }
        return 'FM';
    }
}
