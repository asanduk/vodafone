<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get counts for each status
        $pendingCount = JobApplication::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->count();

        $interviewCount = JobApplication::where('user_id', auth()->id())
            ->where('status', 'interview')
            ->count();

        $rejectedCount = JobApplication::where('user_id', auth()->id())
            ->where('status', 'rejected')
            ->count();

        $offeredCount = JobApplication::where('user_id', auth()->id())
            ->where('status', 'offered')
            ->count();

        // Response Rate Calculation
        $totalApplications = JobApplication::where('user_id', auth()->id())->count();
        $respondedApplications = JobApplication::where('user_id', auth()->id())
            ->whereIn('status', ['interview', 'rejected', 'offered'])
            ->count();
        $responseRate = $totalApplications > 0 
            ? round(($respondedApplications / $totalApplications) * 100) 
            : 0;

        // Last 7 Days Activity
        $dailyStats = JobApplication::select(
            DB::raw('DATE(applied_at) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->where('user_id', auth()->id())
        ->where('applied_at', '>=', Carbon::now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        $dailyLabels = $dailyStats->pluck('date')->map(function($date) {
            return Carbon::parse($date)->format('D');
        });
        $dailyData = $dailyStats->pluck('count');

        // Get monthly statistics for the last 6 months
        $monthlyStats = JobApplication::select(
            DB::raw('DATE_FORMAT(applied_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('user_id', auth()->id())
        ->where('applied_at', '>=', Carbon::now()->subMonths(6))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Prepare data for the chart
        $monthlyLabels = $monthlyStats->pluck('month')->map(function($month) {
            return Carbon::createFromFormat('Y-m', $month)->format('F Y');
        });
        $monthlyData = $monthlyStats->pluck('count');

        return view('dashboard', compact(
            'pendingCount',
            'interviewCount',
            'rejectedCount',
            'offeredCount',
            'monthlyLabels',
            'monthlyData',
            'responseRate',
            'dailyLabels',
            'dailyData'
        ));
    }
}