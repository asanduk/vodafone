<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Contract;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

    public function show(User $user)
    {
        // Get all contracts with related data
        $contracts = $user->contracts()
            ->with(['category', 'subcategory'])
            ->orderBy('contract_date', 'desc')
            ->get();

        // Calculate statistics
        $stats = [
            'total_contracts' => $contracts->count(),
            'total_commission' => $contracts->sum('commission_amount'),
            'monthly_commission' => $contracts->where('contract_date', '>=', now()->startOfMonth())->sum('commission_amount'),
            'yearly_commission' => $contracts->where('contract_date', '>=', now()->startOfYear())->sum('commission_amount'),
            'average_commission' => $contracts->avg('commission_amount'),
            'highest_commission' => $contracts->max('commission_amount'),
            'lowest_commission' => $contracts->min('commission_amount'),
        ];

        // Get monthly breakdown
        $monthlyBreakdown = $contracts->groupBy(function($contract) {
            return Carbon::parse($contract->contract_date)->format('Y-m');
        })->map(function($monthContracts) {
            return [
                'count' => $monthContracts->count(),
                'total' => $monthContracts->sum('commission_amount'),
                'average' => $monthContracts->avg('commission_amount'),
            ];
        });

        return view('admin.users.show', compact('user', 'contracts', 'stats', 'monthlyBreakdown'));
    }
} 