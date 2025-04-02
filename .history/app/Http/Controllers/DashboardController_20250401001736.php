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

        // Ana kategorileri ve kullanıcının sözleşme sayılarını al
        $categories = Category::whereNull('parent_id')
            ->withCount(['contracts' => function($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->get();

        // Kullanıcının toplam komisyonu
        $totalCommission = Contract::where('user_id', $userId)
            ->sum('commission_amount');

        // Kullanıcının bu ayki komisyonu
        $monthlyCommission = Contract::where('user_id', $userId)
            ->whereYear('contract_date', now()->year)
            ->whereMonth('contract_date', now()->month)
            ->sum('commission_amount');

        // Son 6 ayın verilerini hazırla
        $sixMonthsAgo = now()->subMonths(5)->startOfMonth();
        
        // Kullanıcının aylık istatistiklerini al
        $monthlyStats = Contract::where('user_id', $userId)
            ->where('contract_date', '>=', $sixMonthsAgo)
            ->select(DB::raw('
                DATE_FORMAT(contract_date, "%Y-%m") as month,
                COUNT(*) as contract_count,
                SUM(commission_amount) as total_commission
            '))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Debug için
        \Log::info('Raw Monthly Stats:', ['stats' => $monthlyStats->toArray()]);

        // Son 6 ay için veri dizilerini hazırla
        $monthlyData = [];
        $monthlyLabels = [];
        $monthlyCommissions = [];

        // Son 6 ayı döngüyle kontrol et
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            
            $stats = $monthlyStats->firstWhere('month', $monthKey);
            
            $monthlyData[] = $stats ? (int)$stats->contract_count : 0;
            $monthlyCommissions[] = $stats ? (float)$stats->total_commission : 0;
            
            // Almanca ay adı
            setlocale(LC_TIME, 'de_DE.utf8', 'de_DE', 'deu_deu');
            $monthlyLabels[] = ucfirst($date->formatLocalized('%B %Y'));
        }

        // Debug için
        \Log::info('Processed Data:', [
            'labels' => $monthlyLabels,
            'contracts' => $monthlyData,
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