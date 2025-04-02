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

        // Debug için tüm sözleşmeleri kontrol et
        $allContracts = Contract::where('user_id', $userId)
            ->select('id', 'contract_date', 'commission_amount', 'created_at')
            ->orderBy('contract_date', 'desc')
            ->get();

        \Log::info('All Contracts:', [
            'count' => $allContracts->count(),
            'sample' => $allContracts->take(5)->toArray()
        ]);

        // Bugünün istatistikleri
        $todayStats = Contract::where('user_id', $userId)
            ->whereDate('contract_date', today())
            ->selectRaw('COUNT(*) as count, COALESCE(SUM(commission_amount), 0) as commission')
            ->first();

        // Bu haftanın istatistikleri
        $weekStats = Contract::where('user_id', $userId)
            ->whereBetween('contract_date', [
                now()->startOfWeek()->format('Y-m-d'),
                now()->endOfWeek()->format('Y-m-d')
            ])
            ->selectRaw('COUNT(*) as count, COALESCE(SUM(commission_amount), 0) as commission')
            ->first();

        // Bu ayın istatistikleri
        $monthStats = Contract::where('user_id', $userId)
            ->whereBetween('contract_date', [
                now()->startOfMonth()->format('Y-m-d'),
                now()->endOfMonth()->format('Y-m-d')
            ])
            ->selectRaw('COUNT(*) as count, COALESCE(SUM(commission_amount), 0) as commission')
            ->first();

        // Bu yılın istatistikleri
        $yearStats = Contract::where('user_id', $userId)
            ->whereBetween('contract_date', [
                now()->startOfYear()->format('Y-m-d'),
                now()->endOfYear()->format('Y-m-d')
            ])
            ->selectRaw('COUNT(*) as count, COALESCE(SUM(commission_amount), 0) as commission')
            ->first();

        // Debug için istatistikleri logla
        \Log::info('Statistics:', [
            'today' => $todayStats ? $todayStats->toArray() : null,
            'week' => $weekStats ? $weekStats->toArray() : null,
            'month' => $monthStats ? $monthStats->toArray() : null,
            'year' => $yearStats ? $yearStats->toArray() : null
        ]);

        // Ana kategorileri al
        $categories = Category::whereNull('parent_id')
            ->withCount(['contracts' => function($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->get();

        // Son 6 ay için aylık istatistikler
        $monthlyStats = [];
        $monthlyData = [];
        $monthlyLabels = [];
        $monthlyCommissions = [];

        for ($i = 5; $i >= 0; $i--) {
            $startDate = now()->subMonths($i)->startOfMonth()->format('Y-m-d');
            $endDate = now()->subMonths($i)->endOfMonth()->format('Y-m-d');

            $stats = Contract::where('user_id', $userId)
                ->whereBetween('contract_date', [$startDate, $endDate])
                ->selectRaw('COUNT(*) as count, COALESCE(SUM(commission_amount), 0) as commission')
                ->first();

            $monthlyData[] = $stats ? $stats->count : 0;
            $monthlyCommissions[] = $stats ? (float)$stats->commission : 0;
            
            setlocale(LC_TIME, 'de_DE.utf8', 'de_DE', 'deu_deu');
            $monthlyLabels[] = ucfirst(now()->subMonths($i)->formatLocalized('%B %Y'));
        }

        // Debug için aylık istatistikleri logla
        \Log::info('Monthly Stats:', [
            'data' => array_combine($monthlyLabels, array_map(function($count, $commission) {
                return ['count' => $count, 'commission' => $commission];
            }, $monthlyData, $monthlyCommissions))
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