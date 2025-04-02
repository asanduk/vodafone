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

        // Son 6 ayın verilerini hazırla - SQL sorgusunu düzelttik
        $monthlyStats = Contract::selectRaw('YEAR(contract_date) as year, MONTH(contract_date) as month, COUNT(*) as count')
            ->where('contract_date', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Eksik ayları 0 değeriyle doldur
        $monthlyData = [];
        $monthlyLabels = [];
        
        // Son 6 ayı oluştur
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $year = $date->year;
            $month = $date->month;
            
            // O ay için veri varsa al, yoksa 0 olarak ayarla
            $count = $monthlyStats->first(function($stat) use ($year, $month) {
                return $stat->year == $year && $stat->month == $month;
            });

            $monthlyData[] = $count ? $count->count : 0;
            
            // Ay isimlerini Almanca olarak ayarla
            setlocale(LC_TIME, 'de_DE.utf8', 'de_DE', 'deu_deu');
            $monthLabel = $date->formatLocalized('%B %Y');
            $monthlyLabels[] = $monthLabel;
        }

        \Log::info('Monthly Stats:', [
            'data' => $monthlyData,
            'labels' => $monthlyLabels,
            'raw_stats' => $monthlyStats
        ]);

        return view('dashboard', compact(
            'categories',
            'totalCommission',
            'monthlyCommission',
            'monthlyData',
            'monthlyLabels'
        ));
    }
}