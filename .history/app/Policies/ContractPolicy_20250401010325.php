<?php

namespace App\Policies;

use App\Models\Contract;
use App\Models\User;

class ContractPolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Contract $contract)
    {
        return $user->id === $contract->user_id;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Contract $contract)
    {
        return $user->id === $contract->user_id;
    }

    public function delete(User $user, Contract $contract)
    {
        return $user->id === $contract->user_id;
    }
} 