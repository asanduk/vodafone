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

        // Bugünün istatistikleri
        $todayStats = Contract::where('user_id', $userId)
            ->whereDate('contract_date', today())
            ->select(
                DB::raw('COUNT(*) as count'),
                DB::raw('COALESCE(SUM(commission_amount), 0) as commission')
            )
            ->first();

        // Bu haftanın istatistikleri
        $weekStats = Contract::where('user_id', $userId)
            ->whereBetween('contract_date', [
                now()->startOfWeek()->format('Y-m-d'),
                now()->endOfWeek()->format('Y-m-d')
            ])
            ->select(
                DB::raw('COUNT(*) as count'),
                DB::raw('COALESCE(SUM(commission_amount), 0) as commission')
            )
            ->first();

        // Bu ayın istatistikleri
        $monthStats = Contract::where('user_id', $userId)
            ->whereBetween('contract_date', [
                now()->startOfMonth()->format('Y-m-d'),
                now()->endOfMonth()->format('Y-m-d')
            ])
            ->select(
                DB::raw('COUNT(*) as count'),
                DB::raw('COALESCE(SUM(commission_amount), 0) as commission')
            )
            ->first();

        // Bu yılın istatistikleri
        $yearStats = Contract::where('user_id', $userId)
            ->whereBetween('contract_date', [
                now()->startOfYear()->format('Y-m-d'),
                now()->endOfYear()->format('Y-m-d')
            ])
            ->select(
                DB::raw('COUNT(*) as count'),
                DB::raw('COALESCE(SUM(commission_amount), 0) as commission')
            )
            ->first();

        // Debug için SQL sorgularını logla
        \Log::info('Today Query', [
            'sql' => Contract::where('user_id', $userId)
                ->whereDate('contract_date', today())
                ->select(
                    DB::raw('COUNT(*) as count'),
                    DB::raw('COALESCE(SUM(commission_amount), 0) as commission')
                )
                ->toSql(),
            'bindings' => [
                'user_id' => $userId,
                'date' => today()->format('Y-m-d')
            ],
            'results' => $todayStats
        ]);

        // Ana kategorileri al
        $categories = Category::whereNull('parent_id')
            ->withCount(['contracts' => function($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->get();

        // Son 6 ay için aylık istatistikler
        $monthlyStats = Contract::where('user_id', $userId)
            ->where('contract_date', '>=', now()->subMonths(5)->startOfMonth()->format('Y-m-d'))
            ->select(
                DB::raw('DATE_FORMAT(contract_date, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count'),
                DB::raw('COALESCE(SUM(commission_amount), 0) as commission')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyData = [];
        $monthlyLabels = [];
        $monthlyCommissions = [];

        // Son 6 ay için veri hazırla
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            
            $stats = $monthlyStats->firstWhere('month', $monthKey);
            
            $monthlyData[] = $stats ? (int)$stats->count : 0;
            $monthlyCommissions[] = $stats ? (float)$stats->commission : 0;
            
            setlocale(LC_TIME, 'de_DE.utf8', 'de_DE', 'deu_deu');
            $monthlyLabels[] = ucfirst($date->formatLocalized('%B %Y'));
        }

        // Null değerleri 0 olarak ayarla ve float değerleri düzelt
        $todayStats = (object)[
            'count' => $todayStats->count ?? 0,
            'commission' => (float)($todayStats->commission ?? 0)
        ];
        
        $weekStats = (object)[
            'count' => $weekStats->count ?? 0,
            'commission' => (float)($weekStats->commission ?? 0)
        ];
        
        $monthStats = (object)[
            'count' => $monthStats->count ?? 0,
            'commission' => (float)($monthStats->commission ?? 0)
        ];
        
        $yearStats = (object)[
            'count' => $yearStats->count ?? 0,
            'commission' => (float)($yearStats->commission ?? 0)
        ];

        // Debug için sonuçları logla
        \Log::info('Statistics Results', [
            'today' => $todayStats,
            'week' => $weekStats,
            'month' => $monthStats,
            'year' => $yearStats
        ]);

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