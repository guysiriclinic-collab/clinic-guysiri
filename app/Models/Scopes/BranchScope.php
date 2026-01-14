<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * BranchScope - Automatically filters queries by the currently selected branch
 *
 * This scope is applied to Transaction models (Treatment, Queue, DfPayment, etc.)
 * to ensure data isolation - users can only see transactions from their current branch.
 *
 * CRITICAL SECURITY: Prevents cross-branch data leakage
 */
class BranchScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Check if user is Super Admin (username: admin)
        // Super Admin can see ALL branches without filtering
        if (auth()->check() && auth()->user()->username === 'admin') {
            return; // Skip branch filtering for Super Admin
        }

        // Get the currently selected branch from session
        $selectedBranchId = session('selected_branch_id');

        // Only apply branch filter if a branch is selected
        // This allows Admin/Area Manager to work after selecting a branch
        if ($selectedBranchId) {
            $builder->where($model->getTable() . '.branch_id', $selectedBranchId);
        }
    }
}
