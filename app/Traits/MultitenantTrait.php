<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait MultitenantTrait
{
    protected static function bootMultitenantTrait()
    {
        // Automatically add company_id when creating
        static::creating(function ($model) {
            if (Auth::check() && !$model->company_id) {
                $model->company_id = Auth::user()->company_id;
            }
        });

        // Automatically scope all queries to current user's company
        static::addGlobalScope('company', function (Builder $builder) {
            if (Auth::check()) {
                $builder->where('company_id', Auth::user()->company_id);
            }
        });
    }
}
