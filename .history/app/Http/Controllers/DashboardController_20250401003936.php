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
        $todayStats = DB::select("
            SELECT 
                COUNT(*) as count, 
                SUM(commission_amount) as commission
            FROM contracts 
            WHERE user_id = ? 
            AND DATE(contract_date) = ?", 
            [$userId, date('Y-m-d')]
        )[0];

        // Bu haftanın istatistikleri
        $weekStats = DB::select("
            SELECT 
                COUNT(*) as count, 
                SUM(commission_amount) as commission
            FROM contracts 
            WHERE user_id = ? 
            AND contract_date BETWEEN ? AND ?",
            [
                $userId,
                now()->startOfWeek()->format('Y-m-d'),
                now()->endOfWeek()->format('Y-m-d')
            ]
        )[0];

        // Bu ayın istatistikleri
        $monthStats = DB::select("
            SELECT 
                COUNT(*) as count, 
                SUM(commission_amount) as commission
            FROM contracts 
            WHERE user_id = ? 
            AND contract_date BETWEEN ? AND ?",
            [
                $userId,
                now()->startOfMonth()->format('Y-m-d'),
                now()->endOfMonth()->format('Y-m-d')
            ]
        )[0];

        // Bu yılın istatistikleri
        $yearStats = DB::select("
            SELECT 
                COUNT(*) as count, 
                SUM(commission_amount) as commission
            FROM contracts 
            WHERE user_id = ? 
            AND contract_date BETWEEN ? AND ?",
            [
                $userId,
                now()->startOfYear()->format('Y-m-d'),
                now()->endOfYear()->format('Y-m-d')
            ]
        )[0];

        // Debug için tüm sözleşmeleri kontrol et
        $allContracts = DB::select("
            SELECT 
                c.id,
                c.contract_date,
                c.contract_number,
                c.customer_name,
                c.commission_amount,
                cat.name as category_name,
                sub.name as subcategory_name
            FROM contracts c
            LEFT JOIN categories cat ON c.category_id = cat.id
            LEFT JOIN categories sub ON c.subcategory_id = sub.id
            WHERE c.user_id = ?
            ORDER BY c.contract_date DESC",
            [$userId]
        );

        // Debug için logla
        \Log::info('Debug Bilgileri:', [
            'user_id' => $userId,
            'today' => $todayStats,
            'week' => $weekStats,
            'month' => $monthStats,
            'year' => $yearStats,
            'sample_contracts' => array_slice($allContracts, 0, 3)
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