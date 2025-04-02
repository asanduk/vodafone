<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Contract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Enable query logging
        DB::enableQueryLog();

        // Bugünün istatistikleri
        $todayStats = DB::table('contracts')
            ->where('user_id', $userId)
            ->whereDate('contract_date', Carbon::today())
            ->select(
                DB::raw('COUNT(*) as count'),
                DB::raw('CAST(IFNULL(SUM(commission_amount), 0) AS DECIMAL(10,2)) as commission')
            )
            ->first();

        // Bu haftanın istatistikleri
        $weekStats = DB::table('contracts')
            ->where('user_id', $userId)
            ->whereBetween('contract_date', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])
            ->select(
                DB::raw('COUNT(*) as count'),
                DB::raw('CAST(IFNULL(SUM(commission_amount), 0) AS DECIMAL(10,2)) as commission')
            )
            ->first();

        // Bu ayın istatistikleri
        $monthStats = DB::table('contracts')
            ->where('user_id', $userId)
            ->whereBetween('contract_date', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
            ->select(
                DB::raw('COUNT(*) as count'),
                DB::raw('CAST(IFNULL(SUM(commission_amount), 0) AS DECIMAL(10,2)) as commission')
            )
            ->first();

        // Bu yılın istatistikleri
        $yearStats = DB::table('contracts')
            ->where('user_id', $userId)
            ->whereBetween('contract_date', [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear()
            ])
            ->select(
                DB::raw('COUNT(*) as count'),
                DB::raw('CAST(IFNULL(SUM(commission_amount), 0) AS DECIMAL(10,2)) as commission')
            )
            ->first();

        // Log the queries
        \Log::info('SQL Queries:', [
            'queries' => DB::getQueryLog(),
            'today' => $todayStats,
            'week' => $weekStats,
            'month' => $monthStats,
            'year' => $yearStats
        ]);

        // Ana kategorileri al
        $categories = Category::whereNull('parent_id')
            ->withCount(['contracts' => function($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->get();

        // Son 6 ay için aylık istatistikler
        $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();
        $monthlyStats = DB::table('contracts')
            ->where('user_id', $userId)
            ->where('contract_date', '>=', $sixMonthsAgo)
            ->select(
                DB::raw('DATE_FORMAT(contract_date, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count'),
                DB::raw('CAST(IFNULL(SUM(commission_amount), 0) AS DECIMAL(10,2)) as commission')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyData = [];
        $monthlyLabels = [];
        $monthlyCommissions = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            
            $stats = $monthlyStats->where('month', $monthKey)->first();
            
            $monthlyData[] = $stats ? (int)$stats->count : 0;
            $monthlyCommissions[] = $stats ? (float)$stats->commission : 0;
            
            setlocale(LC_TIME, 'de_DE.utf8', 'de_DE', 'deu_deu');
            $monthlyLabels[] = ucfirst($date->formatLocalized('%B %Y'));
        }

        // Convert stats to objects if they're null
        $todayStats = $todayStats ?: (object)['count' => 0, 'commission' => 0];
        $weekStats = $weekStats ?: (object)['count' => 0, 'commission' => 0];
        $monthStats = $monthStats ?: (object)['count' => 0, 'commission' => 0];
        $yearStats = $yearStats ?: (object)['count' => 0, 'commission' => 0];

        return view('dashboard', compact(
            'categories',
            'monthlyData',
            'monthlyLabels',
            'monthlyCommissions',
            'todayStats',
            'weekStats',
            'monthStats',
            'yearStats'
        ));
    }
}