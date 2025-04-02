<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Category;
use App\Models\Contract;

class DashboardController extends Controller
{
    public function index()
    {
        // Ana kategorileri ve sözleşme sayılarını al
        $categories = Category::whereNull('parent_id')
            ->withCount('contracts')
            ->get();

        // Toplam komisyon
        $totalCommission = Contract::sum('commission_amount');

        // Bu ayki komisyon
        $monthlyCommission = Contract::whereYear('contract_date', now()->year)
            ->whereMonth('contract_date', now()->month)
            ->sum('commission_amount');

        // Günlük komisyon
        $dailyCommission = Contract::whereDate('contract_date', today())->sum('commission_amount');

        // Haftalık komisyon
        $weeklyCommission = Contract::whereBetween('contract_date', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->sum('commission_amount');

        // Yıllık komisyon
        $yearlyCommission = Contract::whereYear('contract_date', now()->year)->sum('commission_amount');

        // Kullanıcı sıralaması ve performans karşılaştırması
        $userRankings = DB::table('users')
            ->select('users.name', 
                    DB::raw('COUNT(contracts.id) as total_contracts'),
                    DB::raw('SUM(contracts.commission_amount) as total_commission'),
                    DB::raw('AVG(contracts.commission_amount) as avg_commission'))
            ->leftJoin('contracts', 'users.id', '=', 'contracts.user_id')
            ->where('contracts.contract_date', '>=', now()->subMonths(3))
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_commission')
            ->limit(5)
            ->get();

        // Ortalama performans metrikleri
        $averageMetrics = DB::table('contracts')
            ->select(
                DB::raw('AVG(commission_amount) as avg_commission'),
                DB::raw('COUNT(*) / 3 as avg_monthly_contracts')
            )
            ->where('contract_date', '>=', now()->subMonths(3))
            ->first();

        // Son 6 ayın verilerini hazırla
        $monthlyStats = Contract::selectRaw('DATE_FORMAT(contract_date, "%Y-%m") as month, COUNT(*) as count')
            ->where('contract_date', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Eksik ayları 0 değeriyle doldur
        $monthlyData = [];
        $monthlyLabels = [];
        
        // Son 6 ayı oluştur
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $monthLabel = $date->format('M Y'); // Ay adı ve yıl

            // O ay için veri varsa al, yoksa 0 olarak ayarla
            $count = $monthlyStats->firstWhere('month', $monthKey);
            $monthlyData[] = $count ? $count->count : 0;
            $monthlyLabels[] = $monthLabel;
        }

        return view('dashboard', compact(
            'categories',
            'totalCommission',
            'monthlyCommission',
            'dailyCommission',
            'weeklyCommission',
            'yearlyCommission',
            'userRankings',
            'averageMetrics',
            'monthlyData',
            'monthlyLabels'
        ));
    }
}