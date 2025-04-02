<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{
    public function dashboard()
    {
        // Ana kategorileri ve sözleşme sayılarını al
        $categories = Category::withCount('contracts')->get();

        // Toplam komisyon
        $totalCommission = Contract::sum('commission_amount');

        // Bu ayki komisyon
        $monthlyCommission = Contract::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('commission_amount');

        // Son 12 ayın aylık verilerini al
        $monthlyStats = Contract::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(commission_amount) as commission')
        )
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Grafik için veri hazırla
        $monthlyData = $monthlyStats->pluck('total')->toArray();
        $monthlyLabels = $monthlyStats->pluck('month')->map(function($month) {
            return Carbon::createFromFormat('Y-m', $month)->format('M Y');
        })->toArray();

        return view('dashboard', compact(
            'categories',
            'totalCommission',
            'monthlyCommission',
            'monthlyData',
            'monthlyLabels'
        ));
    }
} 