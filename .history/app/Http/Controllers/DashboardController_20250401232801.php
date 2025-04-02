<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Contract;
use App\Models\Goal;
use App\Models\Achievement;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $now = Carbon::now();
        $startOfWeek = $now->copy()->startOfWeek();
        $startOfMonth = $now->copy()->startOfMonth();

        // Weekly Top Performers
        $topPerformers = User::withCount(['contracts' => function($query) use ($startOfWeek) {
            $query->where('created_at', '>=', $startOfWeek);
        }])
        ->withSum(['contracts' => function($query) use ($startOfWeek) {
            $query->where('created_at', '>=', $startOfWeek);
        }], 'commission')
        ->orderByDesc('contracts_sum_commission')
        ->limit(5)
        ->get();

        // Monthly Top Performers
        $monthlyTopPerformers = User::withCount(['contracts' => function($query) use ($startOfMonth) {
            $query->where('created_at', '>=', $startOfMonth);
        }])
        ->withSum(['contracts' => function($query) use ($startOfMonth) {
            $query->where('created_at', '>=', $startOfMonth);
        }], 'commission')
        ->orderByDesc('contracts_sum_commission')
        ->limit(5)
        ->get();

        // User's Weekly Stats
        $userWeeklyContracts = $user->contracts()
            ->where('created_at', '>=', $startOfWeek)
            ->count();

        $userWeeklyCommission = $user->contracts()
            ->where('created_at', '>=', $startOfWeek)
            ->sum('commission');

        // User's Monthly Stats
        $userMonthlyContracts = $user->contracts()
            ->where('created_at', '>=', $startOfMonth)
            ->count();

        $userMonthlyCommission = $user->contracts()
            ->where('created_at', '>=', $startOfMonth)
            ->sum('commission');

        // User's Rank
        $userRank = User::withSum(['contracts' => function($query) use ($startOfWeek) {
            $query->where('created_at', '>=', $startOfWeek);
        }], 'commission')
        ->orderByDesc('contracts_sum_commission')
        ->pluck('id')
        ->search($user->id) + 1;

        $userMonthlyRank = User::withSum(['contracts' => function($query) use ($startOfMonth) {
            $query->where('created_at', '>=', $startOfMonth);
        }], 'commission')
        ->orderByDesc('contracts_sum_commission')
        ->pluck('id')
        ->search($user->id) + 1;

        // Goals
        $monthlyGoal = Goal::where('user_id', $user->id)
            ->where('type', 'monthly_contracts')
            ->where('month', $now->format('Y-m'))
            ->first();

        $commissionGoal = Goal::where('user_id', $user->id)
            ->where('type', 'commission')
            ->where('month', $now->format('Y-m'))
            ->first();

        // Calculate Progress
        $monthlyGoalProgress = $monthlyGoal ? 
            min(100, ($userMonthlyContracts / $monthlyGoal->target) * 100) : 0;

        $commissionGoalProgress = $commissionGoal ? 
            min(100, ($userMonthlyCommission / $commissionGoal->target) * 100) : 0;

        // Achievements
        $achievements = Achievement::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return view('dashboard', compact(
            'topPerformers',
            'monthlyTopPerformers',
            'userWeeklyContracts',
            'userWeeklyCommission',
            'userMonthlyContracts',
            'userMonthlyCommission',
            'userRank',
            'userMonthlyRank',
            'monthlyGoal',
            'commissionGoal',
            'monthlyGoalProgress',
            'commissionGoalProgress',
            'achievements'
        ));
    }

    public function setGoal(Request $request)
    {
        $request->validate([
            'type' => 'required|in:monthly_contracts,commission',
            'target' => 'required|numeric|min:0',
            'month' => 'required|date_format:Y-m'
        ]);

        $user = auth()->user();

        Goal::updateOrCreate(
            [
                'user_id' => $user->id,
                'type' => $request->type,
                'month' => $request->month
            ],
            [
                'target' => $request->target
            ]
        );

        return response()->json(['message' => 'Hedef başarıyla güncellendi']);
    }
}