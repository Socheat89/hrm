<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Avoid infinite loop by using hasUser instead of check
        if (Auth::hasUser() && Auth::user()->company_id) {
            $builder->where($model->getTable() . '.company_id', Auth::user()->company_id);
        }
    }
}
