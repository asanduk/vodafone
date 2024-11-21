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

        // Get monthly statistics for the last 12 months
        $monthlyStats = JobApplication::select(
            DB::raw('DATE_FORMAT(applied_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as total_applications'),
            DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending'),
            DB::raw('SUM(CASE WHEN status = "interview" THEN 1 ELSE 0 END) as interview'),
            DB::raw('SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected'),
            DB::raw('SUM(CASE WHEN status = "offered" THEN 1 ELSE 0 END) as offered')
        )
        ->where('user_id', auth()->id())
        ->where('applied_at', '>=', now()->subMonths(12))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Prepare data for the chart
        $monthlyLabels = $monthlyStats->pluck('month')->map(function($month) {
            return Carbon::createFromFormat('Y-m', $month)->format('M Y');
        })->toArray();

        $monthlyData = $monthlyStats->pluck('total_applications')->toArray();

        // Additional statistics
        $totalApplications = array_sum($monthlyData);
        $averageApplications = $monthlyStats->count() > 0 
            ? round($totalApplications / $monthlyStats->count(), 1) 
            : 0;

        $statusDistribution = [
            'pending' => $pendingCount,
            'interview' => $interviewCount,
            'rejected' => $rejectedCount,
            'offered' => $offeredCount
        ];

        $responseRate = [
            'response_rate' => $totalApplications > 0 
                ? round((($interviewCount + $rejectedCount + $offeredCount) / $totalApplications) * 100, 1) 
                : 0,
            'interview_rate' => $totalApplications > 0 
                ? round(($interviewCount / $totalApplications) * 100, 1) 
                : 0,
            'success_rate' => $totalApplications > 0 
                ? round(($offeredCount / $totalApplications) * 100, 1) 
                : 0
        ];

        $weeklyActivity = JobApplication::select(
            DB::raw('DAYNAME(applied_at) as day_name'),
            DB::raw('COUNT(*) as applications')
        )
        ->where('user_id', auth()->id())
        ->groupBy('day_name')
        ->orderByRaw('FIELD(day_name, "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday")')
        ->get();

        $successTrend = JobApplication::select(
            DB::raw('DATE_FORMAT(applied_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN status = "offered" THEN 1 ELSE 0 END) as success')
        )
        ->where('user_id', auth()->id())
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->map(function($item) {
            return [
                'month' => $item->month,
                'rate' => $item->total > 0 ? ($item->success / $item->total) * 100 : 0
            ];
        });

        return view('dashboard', compact(
            'pendingCount',
            'interviewCount',
            'rejectedCount',
            'offeredCount',
            'monthlyLabels',
            'monthlyData',
            'totalApplications',
            'averageApplications',
            'statusDistribution',
            'responseRate',
            'weeklyActivity',
            'successTrend'
        ));
    }
} 