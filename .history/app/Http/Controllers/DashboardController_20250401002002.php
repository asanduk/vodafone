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

        // Tüm sözleşmeleri veritabanından direkt sorgula
        $contracts = Contract::where('user_id', $userId)
            ->orderBy('contract_date', 'desc')
            ->get();

        // Bugünün istatistikleri
        $todayStats = [
            'count' => $contracts->where('contract_date', today())->count(),
            'commission' => $contracts->where('contract_date', today())->sum('commission_amount')
        ];

        // Bu haftanın istatistikleri
        $weekStats = [
            'count' => $contracts->whereBetween('contract_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'commission' => $contracts->whereBetween('contract_date', [now()->startOfWeek(), now()->endOfWeek()])->sum('commission_amount')
        ];

        // Bu ayın istatistikleri
        $monthStats = [
            'count' => $contracts->whereBetween('contract_date', [now()->startOfMonth(), now()->endOfMonth()])->count(),
            'commission' => $contracts->whereBetween('contract_date', [now()->startOfMonth(), now()->endOfMonth()])->sum('commission_amount')
        ];

        // Bu yılın istatistikleri
        $yearStats = [
            'count' => $contracts->whereBetween('contract_date', [now()->startOfYear(), now()->endOfYear()])->count(),
            'commission' => $contracts->whereBetween('contract_date', [now()->startOfYear(), now()->endOfYear()])->sum('commission_amount')
        ];

        // Ana kategorileri al
        $categories = Category::whereNull('parent_id')
            ->withCount(['contracts' => function($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->get();

        // Son 6 ay için aylık istatistikler
        $monthlyStats = [];
        $monthlyLabels = [];
        $monthlyData = [];
        $monthlyCommissions = [];

        for ($i = 5; $i >= 0; $i--) {
            $startDate = now()->subMonths($i)->startOfMonth();
            $endDate = now()->subMonths($i)->endOfMonth();

            $monthContracts = $contracts->whereBetween('contract_date', [$startDate, $endDate]);
            
            $monthlyData[] = $monthContracts->count();
            $monthlyCommissions[] = $monthContracts->sum('commission_amount');
            
            setlocale(LC_TIME, 'de_DE.utf8', 'de_DE', 'deu_deu');
            $monthlyLabels[] = ucfirst($startDate->formatLocalized('%B %Y'));
        }

        // Debug için
        \Log::info('Monthly Statistics:', [
            'labels' => $monthlyLabels,
            'contracts' => $monthlyData,
            'commissions' => $monthlyCommissions
        ]);

        return view('dashboard', compact(
            'contracts',
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