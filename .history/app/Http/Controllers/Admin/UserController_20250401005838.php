<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Contract;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['contracts' => function($query) {
            $query->select('user_id', 'commission_amount', 'contract_date');
        }])
        ->get()
        ->map(function($user) {
            $user->total_commission = $user->contracts->sum('commission_amount');
            $user->monthly_commission = $user->contracts
                ->where('contract_date', '>=', now()->startOfMonth())
                ->where('contract_date', '<=', now()->endOfMonth())
                ->sum('commission_amount');
            $user->yearly_commission = $user->contracts
                ->where('contract_date', '>=', now()->startOfYear())
                ->where('contract_date', '<=', now()->endOfYear())
                ->sum('commission_amount');
            return $user;
        });

        return view('admin.users.index', compact('users'));
    }
} 