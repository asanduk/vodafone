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

        // Tüm sözleşmeleri direkt SQL ile çek
        $todayStats = DB::select("
            SELECT COUNT(*) as count, IFNULL(SUM(commission_amount), 0) as commission
            FROM contracts 
            WHERE user_id = ? 
            AND DATE(contract_date) = CURDATE()", 
            [$userId]
        )[0];

        $weekStats = DB::select("
            SELECT COUNT(*) as count, IFNULL(SUM(commission_amount), 0) as commission
            FROM contracts 
            WHERE user_id = ? 
            AND contract_date BETWEEN ? AND ?",
            [
                $userId,
                now()->startOfWeek()->format('Y-m-d'),
                now()->endOfWeek()->format('Y-m-d')
            ]
        )[0];

        $monthStats = DB::select("
            SELECT COUNT(*) as count, IFNULL(SUM(commission_amount), 0) as commission
            FROM contracts 
            WHERE user_id = ? 
            AND contract_date BETWEEN ? AND ?",
            [
                $userId,
                now()->startOfMonth()->format('Y-m-d'),
                now()->endOfMonth()->format('Y-m-d')
            ]
        )[0];

        $yearStats = DB::select("
            SELECT COUNT(*) as count, IFNULL(SUM(commission_amount), 0) as commission
            FROM contracts 
            WHERE user_id = ? 
            AND contract_date BETWEEN ? AND ?",
            [
                $userId,
                now()->startOfYear()->format('Y-m-d'),
                now()->endOfYear()->format('Y-m-d')
            ]
        )[0];

        // Son 6 ay için aylık istatistikler
        $monthlyStats = DB::select("
            SELECT 
                DATE_FORMAT(contract_date, '%Y-%m') as month,
                COUNT(*) as count,
                IFNULL(SUM(commission_amount), 0) as commission
            FROM contracts 
            WHERE user_id = ?
            AND contract_date >= DATE_SUB(CURDATE(), INTERVAL 5 MONTH)
            GROUP BY DATE_FORMAT(contract_date, '%Y-%m')
            ORDER BY month ASC",
            [$userId]
        );

        // Debug için tüm sözleşmeleri göster
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

        // Debug için SQL sorgularını logla
        \Log::info('SQL Debug:', [
            'user_id' => $userId,
            'today_count' => $todayStats->count ?? 0,
            'today_commission' => $todayStats->commission ?? 0,
            'week_count' => $weekStats->count ?? 0,
            'week_commission' => $weekStats->commission ?? 0,
            'month_count' => $monthStats->count ?? 0,
            'month_commission' => $monthStats->commission ?? 0,
            'year_count' => $yearStats->count ?? 0,
            'year_commission' => $yearStats->commission ?? 0,
            'all_contracts_count' => count($allContracts)
        ]);

        $monthlyData = [];
        $monthlyLabels = [];
        $monthlyCommissions = [];

        // Son 6 ay için veri hazırla
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
            'allContracts' // Debug için eklendi
        ));
    }
}