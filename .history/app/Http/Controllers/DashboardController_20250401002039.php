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
                DB::raw('SUM(commission_amount) as commission')
            )
            ->first();

        // Bu haftanın istatistikleri
        $weekStats = Contract::where('user_id', $userId)
            ->whereBetween('contract_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->select(
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(commission_amount) as commission')
            )
            ->first();

        // Bu ayın istatistikleri
        $monthStats = Contract::where('user_id', $userId)
            ->whereBetween('contract_date', [now()->startOfMonth(), now()->endOfMonth()])
            ->select(
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(commission_amount) as commission')
            )
            ->first();

        // Bu yılın istatistikleri
        $yearStats = Contract::where('user_id', $userId)
            ->whereBetween('contract_date', [now()->startOfYear(), now()->endOfYear()])
            ->select(
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(commission_amount) as commission')
            )
            ->first();

        // Ana kategorileri al
        $categories = Category::whereNull('parent_id')
            ->withCount(['contracts' => function($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->get();

        // Son 6 ay için aylık istatistikler
        $monthlyStats = Contract::where('user_id', $userId)
            ->where('contract_date', '>=', now()->subMonths(5)->startOfMonth())
            ->select(
                DB::raw('DATE_FORMAT(contract_date, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(commission_amount) as commission')
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
            
            $monthlyData[] = $stats ? $stats->count : 0;
            $monthlyCommissions[] = $stats ? $stats->commission : 0;
            
            setlocale(LC_TIME, 'de_DE.utf8', 'de_DE', 'deu_deu');
            $monthlyLabels[] = ucfirst($date->formatLocalized('%B %Y'));
        }

        // Null değerleri 0 olarak ayarla
        $todayStats = $todayStats ?? (object)['count' => 0, 'commission' => 0];
        $weekStats = $weekStats ?? (object)['count' => 0, 'commission' => 0];
        $monthStats = $monthStats ?? (object)['count' => 0, 'commission' => 0];
        $yearStats = $yearStats ?? (object)['count' => 0, 'commission' => 0];

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