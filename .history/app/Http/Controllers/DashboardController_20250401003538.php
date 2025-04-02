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

        // Debug: Veritabanındaki verileri kontrol et
        $allData = DB::select("
            SELECT * FROM contracts WHERE user_id = ?
            ORDER BY contract_date DESC", 
            [$userId]
        );
        \Log::info('Tüm Veriler:', ['data' => $allData]);

        // Bugünün istatistikleri
        $todayStats = DB::select("
            SELECT 
                COUNT(*) as count, 
                COALESCE(SUM(CAST(commission_amount AS DECIMAL(10,2))), 0.00) as commission
            FROM contracts 
            WHERE user_id = ? 
            AND DATE(contract_date) = CURRENT_DATE()", 
            [$userId]
        )[0];

        // Bu haftanın istatistikleri
        $weekStats = DB::select("
            SELECT 
                COUNT(*) as count, 
                COALESCE(SUM(CAST(commission_amount AS DECIMAL(10,2))), 0.00) as commission
            FROM contracts 
            WHERE user_id = ? 
            AND YEARWEEK(contract_date, 1) = YEARWEEK(CURRENT_DATE(), 1)",
            [$userId]
        )[0];

        // Bu ayın istatistikleri
        $monthStats = DB::select("
            SELECT 
                COUNT(*) as count, 
                COALESCE(SUM(CAST(commission_amount AS DECIMAL(10,2))), 0.00) as commission
            FROM contracts 
            WHERE user_id = ? 
            AND YEAR(contract_date) = YEAR(CURRENT_DATE())
            AND MONTH(contract_date) = MONTH(CURRENT_DATE())",
            [$userId]
        )[0];

        // Bu yılın istatistikleri
        $yearStats = DB::select("
            SELECT 
                COUNT(*) as count, 
                COALESCE(SUM(CAST(commission_amount AS DECIMAL(10,2))), 0.00) as commission
            FROM contracts 
            WHERE user_id = ? 
            AND YEAR(contract_date) = YEAR(CURRENT_DATE())",
            [$userId]
        )[0];

        // Debug: İstatistikleri logla
        \Log::info('İstatistikler:', [
            'today' => $todayStats,
            'week' => $weekStats,
            'month' => $monthStats,
            'year' => $yearStats
        ]);

        // Son 6 ay için aylık istatistikler
        $monthlyStats = DB::select("
            SELECT 
                DATE_FORMAT(contract_date, '%Y-%m') as month,
                COUNT(*) as count,
                COALESCE(SUM(CAST(commission_amount AS DECIMAL(10,2))), 0.00) as commission
            FROM contracts 
            WHERE user_id = ?
            AND contract_date >= DATE_SUB(CURRENT_DATE(), INTERVAL 5 MONTH)
            GROUP BY month
            ORDER BY month ASC",
            [$userId]
        );

        // Tüm sözleşmeleri al
        $allContracts = DB::select("
            SELECT 
                c.*,
                cat.name as category_name,
                sub.name as subcategory_name,
                CAST(c.commission_amount AS DECIMAL(10,2)) as commission_amount
            FROM contracts c
            LEFT JOIN categories cat ON c.category_id = cat.id
            LEFT JOIN categories sub ON c.subcategory_id = sub.id
            WHERE c.user_id = ?
            ORDER BY c.contract_date DESC",
            [$userId]
        );

        $monthlyData = [];
        $monthlyLabels = [];
        $monthlyCommissions = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            
            $stats = collect($monthlyStats)->where('month', $monthKey)->first();
            
            $monthlyData[] = $stats ? (int)$stats->count : 0;
            $monthlyCommissions[] = $stats ? (float)$stats->commission : 0;
            
            setlocale(LC_TIME, 'de_DE.utf8', 'de_DE', 'deu_deu');
            $monthlyLabels[] = ucfirst($date->formatLocalized('%B %Y'));
        }

        // Ana kategorileri al
        $categories = Category::whereNull('parent_id')
            ->withCount(['contracts' => function($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->get();

        return view('dashboard', compact(
            'categories',
            'monthlyData',
            'monthlyLabels',
            'monthlyCommissions',
            'todayStats',
            'weekStats',
            'monthStats',
            'yearStats',
            'allContracts'
        ));
    }
}