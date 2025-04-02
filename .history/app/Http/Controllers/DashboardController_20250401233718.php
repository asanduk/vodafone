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
            'monthlyData',
            'monthlyLabels'
        ));
    }
}