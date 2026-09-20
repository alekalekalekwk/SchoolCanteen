<?php

namespace App\Policies;

use App\Models\CreditReport;
use App\Models\User;

class CreditReportPolicy
{
    public function create(User $user): bool
    {
        return $user->role === 'penjual';
    }

    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'penjual';
    }

    public function view(User $user, CreditReport $report): bool
    {
        return $user->role === 'admin' || $user->id === $report->reported_by_id;
    }

    public function update(User $user, CreditReport $report): bool
    {
        return $user->role === 'admin';
    }
}
