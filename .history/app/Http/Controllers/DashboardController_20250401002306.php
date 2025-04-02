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

        // Debug: Veritabanındaki tüm sözleşmeleri kontrol et
        $allContracts = Contract::where('user_id', $userId)
            ->select('id', 'contract_date', 'commission_amount')
            ->orderBy('contract_date', 'desc')
            ->get();

        \Log::info('All Contracts:', ['contracts' => $allContracts->toArray()]);

        // Bugünün istatistikleri - basitleştirilmiş sorgu
        $todayStats = DB::table('contracts')
            ->where('user_id', $userId)
            ->whereDate('contract_date', Carbon::today())
            ->selectRaw('COUNT(*) as count, IFNULL(SUM(commission_amount), 0) as commission')
            ->first();

        // Bu haftanın istatistikleri
        $weekStats = DB::table('contracts')
            ->where('user_id', $userId)
            ->whereBetween('contract_date', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])
            ->selectRaw('COUNT(*) as count, IFNULL(SUM(commission_amount), 0) as commission')
            ->first();

        // Bu ayın istatistikleri
        $monthStats = DB::table('contracts')
            ->where('user_id', $userId)
            ->whereBetween('contract_date', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
            ->selectRaw('COUNT(*) as count, IFNULL(SUM(commission_amount), 0) as commission')
            ->first();

        // Bu yılın istatistikleri
        $yearStats = DB::table('contracts')
            ->where('user_id', $userId)
            ->whereBetween('contract_date', [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear()
            ])
            ->selectRaw('COUNT(*) as count, IFNULL(SUM(commission_amount), 0) as commission')
            ->first();

        // Debug: SQL sorgularını ve sonuçları logla
        \Log::info('Raw SQL Queries', [
            'today_query' => DB::getQueryLog(),
            'today_results' => $todayStats,
            'week_results' => $weekStats,
            'month_results' => $monthStats,
            'year_results' => $yearStats
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
            ->selectRaw('
                DATE_FORMAT(contract_date, "%Y-%m") as month,
                COUNT(*) as count,
                IFNULL(SUM(commission_amount), 0) as commission
            ')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        \Log::info('Monthly Stats:', ['stats' => $monthlyStats->toArray()]);

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

        return view('dashboard', compact(
            'categories',
            'monthlyData',
            'monthlyLabels',
            'monthlyCommissions',
            'todayStats',
            'weekStats',
            'monthStats',
            'yearStats',
            'allContracts' // Debug için eklendi
        ));
    }
}