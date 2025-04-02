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
        $sixMonthsAgo = now()->subMonths(6)->startOfMonth();
        
        // Tüm ayları al ve doğru şekilde grupla
        $monthlyStats = Contract::select(DB::raw('
                DATE_FORMAT(contract_date, "%Y-%m") as date,
                COUNT(*) as total_contracts,
                SUM(commission_amount) as total_commission
            '))
            ->where('contract_date', '>=', $sixMonthsAgo)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Son 6 ayın verilerini hazırla
        $monthlyData = [];
        $monthlyLabels = [];
        $monthlyCommissions = [];

        // Son 6 ayı döngüyle kontrol et
        for ($i = 5; $i >= 0; $i--) {
            $currentDate = now()->subMonths($i);
            $dateKey = $currentDate->format('Y-m');
            
            // Bu ay için kayıt var mı kontrol et
            $monthStats = $monthlyStats->firstWhere('date', $dateKey);
            
            // Verileri dizilere ekle
            $monthlyData[] = $monthStats ? $monthStats->total_contracts : 0;
            $monthlyCommissions[] = $monthStats ? $monthStats->total_commission : 0;
            
            // Almanca ay adını ayarla
            setlocale(LC_TIME, 'de_DE.utf8', 'de_DE', 'deu_deu');
            $monthlyLabels[] = $currentDate->formatLocalized('%B %Y');
        }

        // Debug için logla
        \Log::info('Dashboard Statistics', [
            'monthly_stats' => $monthlyStats,
            'data' => $monthlyData,
            'labels' => $monthlyLabels,
            'commissions' => $monthlyCommissions
        ]);

        return view('dashboard', compact(
            'categories',
            'totalCommission',
            'monthlyCommission',
            'monthlyData',
            'monthlyLabels',
            'monthlyCommissions'
        ));
    }
}