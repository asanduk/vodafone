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
            'monthlyData'
        ));
    }
}