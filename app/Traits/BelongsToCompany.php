<?php

namespace App\Traits;

use App\Models\Scopes\TenantScope;
use Illuminate\Support\Facades\Auth;

trait BelongsToCompany
{
    /**
     * Boot the BelongsToCompany trait for a model.
     *
     * @return void
     */
    protected static function bootBelongsToCompany()
    {
        // Add the Tenant Scope automatically
        static::addGlobalScope(new TenantScope);

        // Auto-assign the company_id when generating new records
        static::creating(function ($model) {
            if (Auth::check() && Auth::user()->company_id && empty($model->company_id)) {
                $model->company_id = Auth::user()->company_id;
            }
        });
    }

    /**
     * Define the relationship to Company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class);
    }
}
