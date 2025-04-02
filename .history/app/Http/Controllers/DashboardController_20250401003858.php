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
        try {
            $userId = Auth::id();

            // Veritabanı bağlantısını test et
            DB::connection()->getPdo();
            
            // Tüm sözleşmeleri kontrol et
            $testQuery = DB::select("SELECT COUNT(*) as total FROM contracts WHERE user_id = ?", [$userId]);
            \Log::info('Test Query Result:', ['result' => $testQuery]);

            // Bugünün istatistikleri
            $todayStats = DB::table('contracts')
                ->where('user_id', $userId)
                ->whereDate('contract_date', today())
                ->select(
                    DB::raw('COUNT(*) as count'),
                    DB::raw('SUM(commission_amount) as commission')
                )
                ->first();

            // Bu haftanın istatistikleri
            $weekStats = DB::table('contracts')
                ->where('user_id', $userId)
                ->whereBetween('contract_date', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])
                ->select(
                    DB::raw('COUNT(*) as count'),
                    DB::raw('SUM(commission_amount) as commission')
                )
                ->first();

            // Bu ayın istatistikleri
            $monthStats = DB::table('contracts')
                ->where('user_id', $userId)
                ->whereBetween('contract_date', [
                    now()->startOfMonth(),
                    now()->endOfMonth()
                ])
                ->select(
                    DB::raw('COUNT(*) as count'),
                    DB::raw('SUM(commission_amount) as commission')
                )
                ->first();

            // Bu yılın istatistikleri
            $yearStats = DB::table('contracts')
                ->where('user_id', $userId)
                ->whereBetween('contract_date', [
                    now()->startOfYear(),
                    now()->endOfYear()
                ])
                ->select(
                    DB::raw('COUNT(*) as count'),
                    DB::raw('SUM(commission_amount) as commission')
                )
                ->first();

            // Tüm sözleşmeleri al
            $allContracts = DB::table('contracts as c')
                ->leftJoin('categories as cat', 'c.category_id', '=', 'cat.id')
                ->leftJoin('categories as sub', 'c.subcategory_id', '=', 'sub.id')
                ->where('c.user_id', $userId)
                ->select(
                    'c.*',
                    'cat.name as category_name',
                    'sub.name as subcategory_name'
                )
                ->orderBy('c.contract_date', 'desc')
                ->get();

            // Ana kategorileri al
            $categories = Category::whereNull('parent_id')
                ->withCount(['contracts' => function($query) use ($userId) {
                    $query->where('user_id', $userId);
                }])
                ->get();

            // Debug için logla
            \Log::info('Dashboard Data:', [
                'user_id' => $userId,
                'today' => $todayStats,
                'week' => $weekStats,
                'month' => $monthStats,
                'year' => $yearStats,
                'contracts_count' => $allContracts->count()
            ]);

            return view('dashboard', compact(
                'categories',
                'todayStats',
                'weekStats',
                'monthStats',
                'yearStats',
                'allContracts'
            ));

        } catch (\Exception $e) {
            \Log::error('Dashboard Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('dashboard')->with('error', 'Verilere erişirken bir hata oluştu.');
        }
    }
}